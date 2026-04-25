<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminProduct extends Model
{
    protected $fillable = [
        'admin_id',
        'products_id',
        'action_type',
        'product_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
