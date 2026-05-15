<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable =[
        'product_id',
        'user_id',
        'body',
        'rating',
        'sentiment',
    ];

    protected $appends = ['is_verified'];

    public function getIsVerifiedAttribute()
    {
        return \App\Models\OrderItem::where('product_id', $this->product_id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', $this->user_id);
            })->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
