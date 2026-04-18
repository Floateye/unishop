<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminProduct extends Model
{
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}