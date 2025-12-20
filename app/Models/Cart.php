<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function cartitem()
    {
        return $this->hasMany(CartItem::class);
    }
}
