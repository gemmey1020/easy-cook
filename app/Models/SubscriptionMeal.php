<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionMeal extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'meal_id', 'meal_date'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
