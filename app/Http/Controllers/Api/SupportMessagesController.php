<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportThread;
use Illuminate\Http\Request;

class SupportMessagesController extends Controller
{
    public function supportThread(Request $request)
    {
        abort_if($request->user()->hasAnyRole(['admin', 'support']), 403);

        $user = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300',
        ]);

        $exists = SupportThread::where('company_id', $user->company_id)
            ->where('user_id', $user->id)
            ->where('title', $validated['title'])
            ->where('discription', $validated['description'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A support thread with the same title and description already exists.',
            ], 422);
        }

        $thread = SupportThread::create([
            'title' => $validated['title'],
            'discription' => $validated['description'],
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);

        return response()->json($thread, 201);
    }

    public function userThreads(Request $request)
    {
        $user = $request->user();

        $query = SupportThread::where('company_id', $user->company_id);

        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->get();
    }

    // public function getMessagesForUser(Request $request, $threadId)
    // {
    //     $query = SupportThread::where('id', $threadId)
    //         ->where('company_id', $request->user()->company_id);

    //     if ($request->user()->hasRole('user')) {
    //         $query->where('user_id', $request->user()->id);
    //     }

    //     $thread = $query->firstOrFail();

    //     $messages = $thread->messages()->orderBy('created_at', 'asc');

    //     if ($request->has('after')) {
    //         $messages->where('created_at', '>', $request->query('after'));
    //     }

    //     return $messages->get();
    // }

    public function getMessagesForUser(Request $request, $threadId)
    {
        $thread = SupportThread::where('id', $threadId)
            ->where('company_id', $request->user()->company_id)
            ->when($request->user()->hasRole('user'), function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        $query = $thread->messages()->orderBy('created_at', 'asc');

        if ($request->has('after_id')) {
            $query->where('id', '>', $request->query('after_id'));
        }

        return $query->get();
    }

    public function supportReply(Request $request, $thread)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
            $attachmentPath = asset('storage/'.$attachmentPath);
        }

        $thread = SupportThread::where('id', $thread)
            ->where('company_id', $request->user()->company_id)
            ->firstOrFail();

        return $thread->messages()->create([
            'sender_id' => $request->user()->id,
            'sender_type' => $request->user()->role,
            'message' => $request->message,
            'attachment' => $attachmentPath,
        ]);
    }

    public function closeThread(Request $request, $threadId)
    {
        $thread = SupportThread::where('id', $threadId)
            ->where('company_id', $request->user()->company_id)
            ->when($request->user()->hasRole('user'), function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->firstOrFail();

        $thread->status = 'closed';
        $thread->save();

        return response()->json([
            'success' => true,
            'message' => 'Support ticket closed.',
        ]);
    }
}
