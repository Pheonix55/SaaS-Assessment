<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getAllUsers(){
       $users= User::with('company')->where('role','!=','admin')->latest()->paginate(10);
        return response()->json([
            'success' => true,
            'data' =>$users,
            'status' => 200
        ]);
    }
    
}
