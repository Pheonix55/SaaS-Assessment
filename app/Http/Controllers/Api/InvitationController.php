<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Mail\SendInvitationEmail;
use App\Models\{Invitation, User};
use DB;

class InvitationController extends Controller
{
    public function invite(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|string',
        ]);
        $emailExits = Invitation::where('email', $request->email)->where('company_id', $request->user()->company_id)->first();

        if ($emailExits) {
            return response()->json(['message' => 'invitaation already exists'], 422);

        }        
        $admin = $request->user();
        if (! $admin->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invitation = Invitation::create([
            'email' => $request->email,
            'invitation_from' => $admin->id,
            'company_id' => $admin->company_id,
            'new_role' => $request->role,
            'token' => Str::uuid(),
            'expiry_date' => now()->addDays(7),
        ]);
        $mailData = [
            'subject' => 'Invitation from '.config('app.name'),
            'route' => Route('invite-accept', ['token' => $invitation->token]),
            'discription' => 'Invitation from '.config('app.name').' to accept the role of '.$request->role,
            'tagline' => 'click the link below to accept the invite',
        ];
        Mail::to($request->email)->send(new SendInvitationEmail($mailData));

        return response()->json([
            'message' => 'Invitation sent successfully',
            'data' => $invitation,
        ], 201);
    }

    public function acceptInvite(Request $request, $token)
    {
        // $token = $request->query('token');
        $invitation = Invitation::where('token', $token)
            ->where('status', Invitation::STATUS_PENDING)
            ->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->update([
                'status' => Invitation::STATUS_EXPIRED,
            ]);

            return response()->json([
                'message' => 'Invitation has expired',
            ], 410);
        }

        $user = User::where('email', $invitation->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'User must register first',
            ], 404);
        }

        if ($user->company_id && $user->company_id !== $invitation->company_id) {
            return response()->json([
                'message' => 'User already belongs to another company',
            ], 409);
        }

        DB::transaction(function () use ($user, $invitation) {
            $oldRole = $user->role;

            $user->update([
                'company_id' => $invitation->company_id,
                'role' => $invitation->new_role,
            ]);

            $invitation->update([
                'previous_role' => $oldRole,
                'status' => Invitation::STATUS_ACCEPTED,
            ]);
        });

        return response()->json([
            'message' => 'Invitation accepted successfully',
        ]);
    }

    public function listInvitationsForAdmin(Request $request)
    {
        $company_id = $request->user()->company_id;
        if (! $company_id) {
            return response()->json([
                'success' => false,
                'message' => 'company id doesnt exist',
            ], 403
            );
        }

        return response()->json([
            'success' => true,
            'data' => Invitation::with('company', 'inviter')->where('company_id', $company_id)->get(),
        ], 200
        );
    }
}
