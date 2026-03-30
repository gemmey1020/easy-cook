<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'company_name',
        'country',
        'phone',
        'user_origin_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles');
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function recoveryToken()
    {
        return $this->hasMany(RecoveryToken::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function hasCompletedProfile()
    {
        return ! empty($this->company_name) && ! empty(Address::where('user_id', $this->id)->first());
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function ownedInvitationCodes()
    {
        return $this->hasMany(InvitationCode::class, 'owner_user_id');
    }

    public function invitationJourneys()
    {
        return $this->hasMany(InvitationJourney::class);
    }
}
