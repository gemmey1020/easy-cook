<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealVariant extends Model
{
    use HasFactory;

    protected $table = 'meal_variants';

    protected $fillable = [
        'meal_id',
        'calorie_level',
        'week',
        'day',
        'meal_type',
        'option_no',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'calorie_level' => 'integer',
        'week' => 'integer',
        'option_no' => 'integer',
    ];

    /* ---------- Relationships ---------- */
    public function translations()
    {
        return $this->hasMany(MealTranslation::class);
    }

    public function nutrition()
    {
        return $this->hasOne(MealNutrition::class);
    }

    public function selectedMeals()
    {
        return $this->hasMany(DailySelectedMeal::class);
    }

    /* ---------- Scopes ---------- */
    public function scopeWeek($q, int $week)
    {
        return $q->where('week', $week);
    }

    public function scopeDay($q, string $day)
    {
        return $q->where('day', $day);
    }
}
