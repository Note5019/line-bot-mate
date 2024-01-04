<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyGold extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'buy_price',
        'value',
        'weight',
        'target_sell_price',
        'target_baht_profit',
        'sold',
    ];

    protected $casts = [
        'buy_price' => 'float',
        'value' => 'float',
        'weight' => 'float',
        'target_sell_price' => 'float',
        'target_baht_profit' => 'float',
        'sold' => 'boolean',
    ];
}
