<?php

namespace App\Events;

use Illuminate\Broadcasting\{InteractsWithSockets, PrivateChannel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\{SupportMessage, User};

class SupportMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(
        public SupportMessage $message,
        public User $user,
        public int $threadId
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('support-thread.'.$this->threadId);

    }

    public function broadcastAs(): string
    {
        return 'SupportMessageSent';
    }

    public function broadcastWith(): array
    {
        $data = [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'message' => $this->message->message,
                'attachment' => $this->message->attachment,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_image' => $this->user->profile_image,
            ],
            'thread_id' => $this->threadId,
        ];

        return $data;
    }
}
