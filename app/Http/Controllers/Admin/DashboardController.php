<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
     public function index()
    {
        // Statistik
        $stats = [
            'total_revenue' => DB::table('orders')->sum('subtotal'),
            'total_orders'  => DB::table('orders')->distinct('order_id')->count('order_id'),
            'pending_orders'=> 0, // belum ada kolom status
            'low_stock'     => Product::where('stock', '<=', 5)->count(),
        ];

        // Recent Orders (group by order_id)
        $recentOrders = DB::table('orders')
            ->select(
                'order_id',
                DB::raw('SUM(subtotal) as total'),
                DB::raw('MAX(created_at) as created_at')
            )
            ->groupBy('order_id')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }




    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
