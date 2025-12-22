<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

class Company extends Model
{
    use Billable;

    protected $fillable = [
        'name', 'stripe_id', 'plan_id', 'email', 'password', 'address', 'phone', 'website', 'status',
    ];

    protected $casts = [
        'status' => CompanyStatus::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getBillableId()
    {
        return $this->stripe_id;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionEvents()
    {
        return $this->hasMany(SubscriptionEvent::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('stripe_status', 'active')
            ->latest()
            ->first();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
