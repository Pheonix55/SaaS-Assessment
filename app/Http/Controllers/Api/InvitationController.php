<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'company_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
        ]);


        Invitation::create([
            'email' => $request->email,
            'company_id' => $request->company_id,
            'token' => Str::random(32),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully',
            'status' => 200
        ]);
    }
}
