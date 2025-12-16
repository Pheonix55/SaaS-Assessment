<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Role, User};
use Illuminate\Container\Attributes\Auth;

class RoleController extends Controller
{


    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $user = User::find($request->user_id);
        if ($user->role = 'admin') {
            return response()->json(['message' => 'Role already assigned as admin'], 401);
        }

        if ($request->role_name == 'user' || $request->role_name == 'support') {

            $user->role = $request->role_name;
            $user->save();

        }
        // onnly admin can assign admin role
        if ($request->role_name == 'admin' && Auth::user()->role=='admin') {
            $user->role = 'admin';
            $user->save();
        }
        return response()->json(['message' => 'Role assigned Successfully', 'data' => $user], 200);
    }
    public function checkRole($id)
    {
        $user = User::find($id);
        return response()->json(['role' => $user->role], 200);
    }
}
