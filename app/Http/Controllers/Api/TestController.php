<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestRoleController extends Controller
{
    public function assign(Request $request): JsonResponse
    {
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $user = $request->user();

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role assigned successfully',
            'user_id' => $user->id,
            'company_id' => $request->company_id,
            'roles' => $user->getRoleNames(),
        ]);
    }
}
