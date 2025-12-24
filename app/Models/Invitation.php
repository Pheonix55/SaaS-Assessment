<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Invitation extends Model
{
    use HasFactory;

    const STATUS_PENDING  = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_EXPIRED  = 'expired';
    const STATUS_REVOKED  = 'revoked';

    protected $fillable = [
        'email',            
        'invitation_from',  
        'company_id',
        'expiry_date',
        'new_role',
        'previous_role',
        'status',
        'token',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invitation_from');
    }

    /* Helpers */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }
}
