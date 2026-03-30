<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'street_address',
        'apartment_suite_unit',
        'town_city',
        'postcode',
        'phone',
        'primary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePrimary($query)
    {
        return $query->where('primary', true);
    }
}
