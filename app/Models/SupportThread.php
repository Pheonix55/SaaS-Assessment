<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportThread extends Model
{
    protected $fillable = [
        'title', 'discription', 'user_id', 'company_id','status'
    ];

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
