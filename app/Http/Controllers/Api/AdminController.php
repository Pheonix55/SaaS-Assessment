<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function createSuperAdmin(Request $request)
    {
        if ($request->user() && ! $request->user()->hasRole('SUPER_ADMIN')) {
            return response()->json([
                'message' => 'Unauthorized, only a super admin can create another admin',
            ], 401);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'company_name' => 'nullable|string|max:255',
        ]);

        $companyName = $request->company_name ?? $request->name.' Company';

        $company = Company::create([
            'name' => $companyName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
            'role' => 'SUPER_ADMIN',
        ]);

        return response()->json([
            'message' => 'Super admin created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company_id' => $user->company_id,
            ],
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
            ],
        ]);
    }
}
