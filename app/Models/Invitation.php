<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'FromEmail',
        'company_id',
        'token',
        'user_id' //to user
    ];    

}
