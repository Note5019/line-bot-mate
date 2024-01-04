<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gold extends Model
{
    use HasFactory;

    const GOLD_WEIGHT = 15.244;
    const GOLD_TYPE = 'HSH';
    const GOLD_CODE = '96.50';

    protected $fillable = [
        'gold_type',
        'gold_code',
        'buy',
        'sell',
        'buy_change',
        'sell_change',
        'time_update',
    ];

    protected $casts = [
        'buy' => 'float',
        'sell' => 'float',
        'buy_change' => 'float',
        'sell_change' => 'float',
    ];
}
