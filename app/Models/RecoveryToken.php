<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token', 'expires_at', 'used', 'not_valid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeValidToken($query)
    {
        return $query->where('expires_at', '>', Carbon::now())->where('used', false)->whereNull('not_valid');
    }
}
