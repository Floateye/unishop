<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'quantity',
        'category_id',
        'slug',
    ];

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


    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_products')
            ->withPivot('action_type')
            ->withTimestamps();
    }
    public function reviews()
    {
        return $this->belongsToMany(Review::class);
    }
}
