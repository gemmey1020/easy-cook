<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Meal extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'meal_id', 'calorie_level', 'day', 'meal_type', 'title', 'description', 'image',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function dailyMeals()
    {
        return $this->hasMany(DailyMeal::class);
    }
}
