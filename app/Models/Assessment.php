<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'city',
        'weight',
        'height',
        'bmi',
        'fat_percentage',
        'muscle_mass',
        'water_percentage',
        'consultation_type',
        'status',
//        'notes',
        'gender',
        'age',
        'goal',
        'birth_date',
        'activity_level',
        'calories_needed',
        'protein',
        'carbohydrates',
        'calories_intake',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(\App\Models\AssessmentNote::class);
    }

    protected $casts = [
        'birth_date' => 'date',
    ];
}
