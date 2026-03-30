<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealNutrition extends Model
{
    use HasFactory;

    protected $table = 'meal_nutritions';

    protected $primaryKey = 'meal_variant_id';

    public $incrementing = false;

    protected $fillable = ['protein', 'carb', 'fat', 'kcal'];

    protected $casts = [
        'protein' => 'decimal:2',
        'carb' => 'decimal:2',
        'fat' => 'decimal:2',
        'kcal' => 'decimal:2',
    ];

    /* ---------- Relationships ---------- */
    public function variant()
    {
        return $this->belongsTo(MealVariant::class, 'meal_variant_id');
    }
}
