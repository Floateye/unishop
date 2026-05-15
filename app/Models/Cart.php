<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{ // Products being saved
    protected $fillable = ['user_id', 'is_open'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}