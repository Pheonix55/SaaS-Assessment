<?php

use App\Models\SupportThread;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('support-thread.{threadId}', function ($user, $threadId) {
    return SupportThread::where('id', $threadId)
        ->where('company_id', $user->company_id)
        ->exists();
});

