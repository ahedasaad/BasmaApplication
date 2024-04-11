<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
/*
|--------------------------------------------------------------------------
| This Controller Contains all the Auth Operation:
| Register- Verify Account- Log in- Log out
|--------------------------------------------------------------------------
*/
    public function register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'mobile_number' => 'required|string',
            ]);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'mobile_number' => $request->input('mobile_number'),
                'account_type' => 'donor',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
