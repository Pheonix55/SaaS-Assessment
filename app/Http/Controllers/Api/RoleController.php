<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|string|in:admin,support,user',
        ]);

        $user = User::findOrFail($request->user_id);
        $authUser = Auth::user();

        if ($user->hasRole($request->role_name)) {
            return response()->json([
                'message' => "User already has the role {$request->role_name}",
            ], 400);
        }

        if ($request->role_name === 'admin' && ! $authUser->hasRole('admin')) {
            return response()->json([
                'message' => 'Only admin can assign admin role',
            ], 403);
        }

        // $user->assignRole($request->role_name); spatie not woriking
        $user->role = $request->role_name;
        $user->save();

        return response()->json([
            'message' => 'Role '.$user->role.' assigned successfully',
            'data' => $user,
        ], 200);
    }

    public function checkRole($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'roles' => $user->getRoleNames(),
        ], 200);
    }

    public function listRoles(Request $request)
    {
        $user = $request->user();

        $roles = Role::where('company_id', $user->company_id)
            ->where('guard_name', 'web')
            ->whereNotIn('name', ['SUPER_ADMIN', 'admin'])
            ->select('id', 'name')
            ->get();

        return response()->json([
            'roles' => $roles,
        ]);
    }
}
