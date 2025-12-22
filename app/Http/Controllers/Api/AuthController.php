<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, DB, Hash};
use App\Enums\CompanyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Models\{Company, Invitation, User};
use Spatie\Permission\PermissionRegistrar;

class AuthController extends Controller
{
    public function listUser(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => User::with('company')->where('company_id', $user->company_id)->where('id', '!=', $user->id)->latest()->paginate(2),
            'status' => 200,
        ]);
    }

    public function getAuthUser(Request $request)
    {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'company_id' => $request->user()->company_id,
            'email' => $request->user()->email,
            'profile_image' => $request->user()->profile_image,
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        $token = $user->createToken('api_token')->plainTextToken;

        $company = $user->company;

        $redirect = route('login'); // default

        if ($company) {
            if ($user->hasRoleInCompany('SUPER_ADMIN', $company->id, 'web')) {
                $redirect = route('superadmin.dashboard');
            } elseif ($user->hasRoleInCompany('admin', $company->id, 'web')) {
                $redirect = route('admin.dashboard');
            } elseif ($user->hasRoleInCompany(['user', 'support'], $company->id, 'web')) {
                $redirect = route('user.dashboard');
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User is not associated with a company',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'redirect' => $redirect,
            ],
            'status' => 200,
        ]);
    }

    // public function login(LoginRequest $request): JsonResponse
    // {
    //     $credentials = $request->validated();

    //     if (! Auth::attempt($credentials)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     Auth::guard('web')->login($user);
    //     $request->session()->regenerate();
    //     $token = $user->createToken('api_token')->plainTextToken;
    //     $redirect = route('login');
    //     $company = $user->company;

    //     if ($user->hasRoleInCompany('SUPER_ADMIN', $company->id, 'web')) {

    //         $redirect = route('superadmin.dashboard');
    //     }
    //     if ($user->hasRoleInCompany('admin', $company->id, 'web')) {

    //         $redirect = route('admin.dashboard');
    //     }

    //     if ($user->hasRoleInCompany(['user', 'support'], $company->id, 'web')) {
    //         $redirect = route('user.dashboard');
    //     }

    //     if (! $company) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User is not associated with a company',
    //         ], 403);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'user' => $user,
    //             'token' => $token,
    //             'redirect' => $redirect,
    //         ],
    //         'status' => 200,
    //     ]);
    // }

    // public function register(RegisterRequest $request)
    // {
    //     $data = $request->validated();
    //     $data['password'] = Hash::make($data['password']);

    //     $invitationToken = $request->query('token');
    //     $companyId = Invitation::where('token', $invitationToken)->value('company_id');
    //     $data['company_id'] = $companyId;

    //     return DB::transaction(function () use ($data, $companyId) {

    //         $user = User::create($data);

    //         if ($companyId) {
    //             app(PermissionRegistrar::class)->setPermissionsTeamId($companyId);

    //             $user->assignRole('user');
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $user,
    //         ], 201);
    //     });
    // }

    public function CompanyRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $company = Company::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
                'status' => CompanyStatus::PENDING,
            ]);

            // Create admin user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $company->id,
                'role' => 'admin',
            ]);
            app(PermissionRegistrar::class)->setPermissionsTeamId($company->id);

            $user->assignRole('admin');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company registered successfully. Awaiting approval.',
                'data' => [
                    'company_id' => $company->id,
                    'status' => $company->status,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed'.$e->getMessage(),
            ], 500);
        }
    }

    public function GetUserProfile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    public function UpdateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            'about' => 'sometimes|nullable|string|max:500',
            'phone' => 'sometimes|nullable|string|max:20',
            'profile_image' => 'sometimes|nullable|image|max:2048', // optional image upload
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('avatars/'), $filename);

            $data['profile_image'] = 'avatars/'.$filename;
        }

        $user->update($data);
        // dd($user,$data);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Profile updated successfully',
        ]);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user(); // or Auth::guard('web')->user();

        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
