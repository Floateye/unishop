<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductDiscounts extends Pivot
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}