<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class Feature extends Model
{
    protected $fillable = [
        'key',
        'name',
    ];

    public function plans()
    {
        return $this->belongsToMany(
            Plan::class,
            'plan_features'
        );
    }
}
