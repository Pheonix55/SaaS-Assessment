<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription;

class Plan extends Model
{
    protected $fillable=[
        'name','price','duration','price_id','stripe_product_id'
    ];
public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

