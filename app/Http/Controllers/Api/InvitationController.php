<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Mail};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\SendInvitationEmail;
use App\Models\{Invitation, User};
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;


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
        // if (!$admin->hasRole('admin')) {
        // return response()->json(['message' => 'Unauthorized'], 403);
        // }
        // dd($admin->roles);

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

    // public function acceptInvite(Request $request, string $token)
    // {
    //     $invitation = Invitation::where('token', $token)
    //         ->where('status', Invitation::STATUS_PENDING)
    //         ->firstOrFail();
    //     if ($invitation->isExpired()) {
    //         $invitation->update([
    //             'status' => Invitation::STATUS_EXPIRED,
    //         ]);

    //         return redirect()->route('login')->with([
    //             'message' => 'This invitation link has expired.',
    //             'type' => 'error',
    //         ]);
    //     }

    //     $user = User::where('email', $invitation->email)->first();
    //     if ($user === null) {

    //         return redirect()->route('user.register', [
    //             'token' => $token,
    //         ])->with([
    //             'message' => 'Please register first using this email to accept the invitation.',
    //             'type' => 'error',
    //         ]);
    //     }

    //     if (
    //         $user->company_id !== null &&
    //         $user->company_id !== $invitation->company_id
    //     ) {
    //         return redirect()->route('login')->with([
    //             'message' => 'You already belong to another company.',
    //             'type' => 'error',
    //         ]);

    //     }
    //     DB::transaction(function () use ($invitation) {
    //         $previousRole = $user->role;

    //         $user->update([
    //             'company_id' => $invitation->company_id,
    //             'role' => $invitation->new_role,
    //         ]);

    //         $invitation->update([
    //             'previous_role' => $previousRole,
    //             'status' => Invitation::STATUS_ACCEPTED,
    //         ]);
    //     });

    //     return redirect()->route('login')->with(['message' => 'Invitation accepted successfully. You are now part of the company.',
    //         'type' => 'success']);

    // }

    public function showInviteRegistrationForm(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', Invitation::STATUS_PENDING)
            ->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->update(['status' => Invitation::STATUS_EXPIRED]);

            return redirect()->route('login')->with([
                'message' => 'This invitation link has expired.',
                'type' => 'error',
            ]);
        }

        return view('auth.user-register', [
            'email' => $invitation->email,
            'token' => $token,
        ]);
    }

    public function registerAndAcceptInvite(RegisterRequest $request, $token)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // dd($data,$token);
        $invitation = Invitation::where('token', $token)
            ->where('status', Invitation::STATUS_PENDING)
            ->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->update(['status' => Invitation::STATUS_EXPIRED]);

            return response()->json([
                'success' => false,
                'message' => 'This invitation link has expired.',
            ], 400);
        }

        return DB::transaction(function () use ($data, $invitation) {
            $user = User::where('email', $invitation->email)->first();

            if ($user) {
                if ($user->company_id !== null && $user->company_id !== $invitation->company_id) {
                    throw new \Exception('You already belong to another company.');
                }

                $previousRole = $user->role;

                $user->update([
                    'company_id' => $invitation->company_id,
                    'role' => $invitation->new_role,
                ]);
            } else {
                // Create new user
                $data['email'] = $invitation->email;
                $data['company_id'] = $invitation->company_id;
                $user = User::create($data);

                app(PermissionRegistrar::class)->setPermissionsTeamId($invitation->company_id);
                $role = Role::firstOrCreate([
                    'name' => $invitation->new_role,
                    'guard_name' => 'web',
                    'company_id' => $user->company_id,
                ]);
                $user->assignRole($role->name);

                $previousRole = null;
            }

            // Update invitation status
            $invitation->update([
                'previous_role' => $previousRole,
                'status' => Invitation::STATUS_ACCEPTED,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invitation accepted successfully. You are now part of the company.',
                'data' => $user,
            ], 201);
        });
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
