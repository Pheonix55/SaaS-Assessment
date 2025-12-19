<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
     protected $fillable = [
        'support_thread_id',
        'sender_id',    
        'sender_type',  
        'message',
        'attachment',   
    ];

    public function supportThread()
    {
        return $this->belongsTo(SupportThread::class);
    }
}
