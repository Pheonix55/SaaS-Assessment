<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Auditable;

    protected $fillable = [
        'company_id',
        'subscription_id',
        'stripe_customer_id',
        'amount',
        'currency',
        'status',
        'payment_method', 'temp_invoice_url',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
