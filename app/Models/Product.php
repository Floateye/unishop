<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'product_discounts')
            ->withTimestamps();
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_products')
            ->withPivot('action_type')
            ->withTimestamps();
    }
}