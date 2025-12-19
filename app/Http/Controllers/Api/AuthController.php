<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};
use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Models\{Company, Role, User};
use Exception;

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
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'status' => 200,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        if ($request->filled('company_id')) {
            $data['company_id'] = $request->company_id;
            $data['role'] = 'user';

            $user = User::create($data);
            // $user->assignRole('user', $request->company_id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'status' => 200,
            ]);
        }
    }

    public function CompanyRegister(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|string|max:255',
            ]);
            $company = Company::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
            ]);

            $data = $request->only(['name', 'email', 'password']);
            $data['company_id'] = $company->id;
            $data['role'] = 'admin';

            // $company->newSubscription('default', $plan->price_id)
            //     ->trialDays(7)
            //     ->create();

            $user = User::create($data);
            $role = Role::where('name', 'admin')
                ->where('guard_name', 'web')
                ->where('company_id', $company->id)
                ->first();
            // $user->assignRole($role);
            $user->role = $role->name;

            return response()->json([
                'success' => true,
                'data' => [
                    'company' => $company,
                    'admin' => $user,
                ],
                'status' => 200,
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
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

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
