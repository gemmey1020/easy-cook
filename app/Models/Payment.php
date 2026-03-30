<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invitation_journey_id',
        'subscription_ids',
        'amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'order_id',
        'payment_date',
        'payment_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitationJourney()
    {
        return $this->belongsTo(InvitationJourney::class);
    }

    protected $casts = [
        'subscription_ids' => 'array',
        'payment_response' => 'array',
        'payment_date' => 'datetime',
    ];
}
