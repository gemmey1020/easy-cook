<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMeal extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'day_id', 'meal_id', 'meal_type'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
