<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cookie_cart_id',
    ];

    protected $appends = [
        'subtotal',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute()
    {
        $subtotal = $this->items->sum(fn($item) => $item->product->price * $item->quantity);
        return round($subtotal, 2);
    }

    public function getTaxAttribute()
    {
        $tax = $this->subtotal * 0.16;
        return round($tax, 2);
    }

    public function getTotalAttribute()
    {
        return $this->subtotal + $this->tax;
    }
}
