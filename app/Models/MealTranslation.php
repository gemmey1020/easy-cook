<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTranslation extends Model
{
    use HasFactory;

    protected $table = 'meal_translations';

    public $timestamps = false;

    protected $fillable = [
        'meal_variant_id',
        'locale',
        'title',
        'description',
    ];

    /* ---------- Relationships ---------- */
    public function variant()
    {
        return $this->belongsTo(MealVariant::class, 'meal_variant_id');
    }
}
