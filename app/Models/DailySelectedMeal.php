<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySelectedMeal extends Model
{
    use HasFactory;

    protected $table = 'daily_selected_meals';

    protected $fillable = [
        'subscription_id',
        'day',
        'meal_variant_id',
        'option_no',
        'meal_type',
    ];

    protected $casts = [
        'option_no' => 'integer',
        'day' => 'date',
    ];

    /* ---------- Relationships ---------- */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function variant()
    {
        return $this->belongsTo(MealVariant::class, 'meal_variant_id');
    }
}
