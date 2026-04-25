<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $casts = [
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
    ];
}
