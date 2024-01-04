<?php

namespace App\Models;

use App\Enums\ResponseCode;
use Illuminate\Database\Eloquent\Model;

// non-table model
class HandlerResponse extends Model
{
    protected $fillable = [
        'code',
        'topic',
        'message',
    ];

    protected $casts = [
        'code' => ResponseCode::class,
    ];
}
