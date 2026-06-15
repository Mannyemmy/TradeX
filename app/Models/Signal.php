<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset',
        'entry_price',
        'take_profit',
        'stop_loss',
        'leverage',
        'status',
    ];
}
