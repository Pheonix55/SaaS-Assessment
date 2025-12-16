<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};
use App\Http\Controllers\Controller;
use App\Http\Requests\{LoginRequest, RegisterRequest};
use App\Models\{Company, User};

class AuthController extends Controller
{
   
    public function listUser(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => User::with('company')->latest()->paginate(10),
            'status' => 200
        ]);
    }

        public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
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
            'status' => 200
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

            return response()->json([
                'success' => true,
                'data' => $user,
                'status' => 200
            ]);
        }
    }
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

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company,
                'admin' => $user,
            ],
            'status' => 200
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
