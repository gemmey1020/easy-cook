<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    CONST ADMIN_ROLE = 1;
    CONST NUTRITION_SPECIALIST_ROLE = 2;
    CONST SALES_ROLE = 3;
    CONST ACCOUNTANT_ROLE = 4;
    CONST DEFAULT_AUTH_USER_ROLE = 5;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_roles');
    }
}
