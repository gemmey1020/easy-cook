<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPackaging extends Model
{
    use HasFactory;

    protected $table = 'daily_packaging';

    protected $fillable = [
        'calorie_level',
        'week',
        'day',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'calorie_level' => 'integer',
        'week' => 'integer',
    ];
}
