<?php
// app/Http/Controllers/MidtransNotificationController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\OrderPaidEvent;

class MidtransNotificationController extends Controller
{
    /**
     * Handle incoming webhook notification from Midtrans.
     * URL: POST /midtrans/notification
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification Received', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;

        // Validasi field penting
        if (!$orderId || !$transactionStatus || !$signatureKey) {
            Log::warning('Midtrans Notification: Missing required fields', $payload);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Validasi signature
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', [
                'order_id' => $orderId,
                'received' => $signatureKey,
                'expected' => $expectedSignature,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order
        $order = Order::with('items.product', 'payment')->where('order_number', $orderId)->first();
        if (!$order) {
            Log::warning("Midtrans Notification: Order not found", ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Idempotency: stop jika order sudah final
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            Log::info("Midtrans Notification: Order already processed", ['order_id' => $orderId]);
            return response()->json(['message' => 'Order already processed'], 200);
        }

        // Update payment record
        $payment = $order->payment;
        if ($payment) {
            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'raw_response' => json_encode($payload),
            ]);
        }

        // Mapping status transaksi
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $this->handlePending($order, $payment, 'Menunggu review fraud');
                } else {
                    $this->handleSuccess($order, $payment);
                }
                break;

            case 'settlement':
                $this->handleSuccess($order, $payment);
                break;

            case 'pending':
                $this->handlePending($order, $payment, 'Menunggu pembayaran');
                break;

            case 'deny':
                $this->handleFailed($order, $payment, 'Pembayaran ditolak');
                break;

            case 'expire':
            case 'cancel':
                $this->handleFailed($order, $payment, 'Pembayaran expired/cancelled');
                break;

            case 'refund':
            case 'partial_refund':
                $this->handleRefund($order, $payment);
                break;

            default:
                Log::info("Midtrans Notification: Unknown status", [
                    'order_id' => $orderId,
                    'status' => $transactionStatus,
                ]);
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }

    /**
     * Handle pembayaran sukses.
     */
    protected function handleSuccess(Order $order, ?Payment $payment): void
    {
        Log::info("Payment SUCCESS for Order: {$order->order_number}");

        $order->update([
            'status' => 'processing',
            'payment_status' => 'paid', // Penting!
        ]);

        if ($payment) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);
        }

        // Event untuk email/notification
        event(new OrderPaidEvent($order));
    }

    /**
     * Handle pembayaran pending.
     */
    protected function handlePending(Order $order, ?Payment $payment, string $message = ''): void
    {
        Log::info("Payment PENDING for Order: {$order->order_number}", ['message' => $message]);

        $order->update(['payment_status' => 'pending']);

        if ($payment) {
            $payment->update(['status' => 'pending']);
        }
    }

    /**
     * Handle pembayaran gagal.
     */
    protected function handleFailed(Order $order, ?Payment $payment, string $reason = ''): void
    {
        Log::info("Payment FAILED for Order: {$order->order_number}", ['reason' => $reason]);

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        // Restock produk
        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }
    }

    /**
     * Handle refund.
     */
    protected function handleRefund(Order $order, ?Payment $payment): void
    {
        Log::info("Payment REFUNDED for Order: {$order->order_number}");

        $order->update(['payment_status' => 'refunded']);

        if ($payment) {
            $payment->update(['status' => 'refunded']);
        }
    }
}
