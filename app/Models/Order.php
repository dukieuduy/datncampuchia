<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_status',
        'payment_method',
        'payment_status',
        'province',
        'district',
        'ward',
        'ship',
        'payment_expires_at',

    ];
    use HasFactory;
    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class, 'order_id', 'id');
    // }
    // Model Order
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    // Mối quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'order_discount', 'order_id', 'discount_id');
    }
    protected $casts = [
        'payment_expires_at' => 'datetime',
    ];

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
    public function guestBuyer()
    {
        return $this->belongsTo(GuestBuyer::class, 'order_id', 'id');
    }


}
