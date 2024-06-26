<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserConfirmationMail;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | This Controller Contains all the Auth Operation:
    | Register- Verify Account- Login- Child Login- Log out- Change Password
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'account_type' => 'donor',
            ]);

            $code = random_int(100000, 999999);

            $registration = Registration::create([
                'user_id' => $user->id,
                'code' => $code,
                'expiration_date' => now()->addDays(3),
            ]);

            Mail::to($user->email)->send(new UserConfirmationMail($code));

            return response()->json(['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
            ]);

            // Find the registration by code
            $registration = Registration::where('code', $request->code)
                ->where('expiration_date', '>=', now())
                ->first();

            if ($registration) {
                // Code is valid, complete the registration process
                $user = $registration->user;
                $user->update(['is_active' => 1]);

                return response()->json(['message' => 'Registration code verified successfully'], 200);
            } else {
                return response()->json(['error' => 'Invalid or expired registration code'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resendCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            // Check if the user has exceeded the allowed number of resend attempts
            // if ($registration->resend_attempts >= config('auth.max_resend_attempts')) {
            //     return response()->json(['error' => 'You have exceeded the maximum number of resend attempts.'], 403);
            // }

            if ($user) {
                // Generate a new registration code
                $code = random_int(100000, 999999);

                // Update the registration code in the database
                $user->registration->update(['code' => $code]);

                // Send email with the new code
                Mail::to($user->email)->send(new UserConfirmationMail($code));

                return response()->json(['message' => 'Registration code resent successfully'], 200);
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);


            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation Error!',
                    'data' => $validate->errors(),
                ], 403);
            }

            $user = User::where('email', $request->email)->first();

            // Check password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('token-name')->plainTextToken;

            if ($user->account_type == 'donor') {
                return response()->json([
                    'token' => $token,
                    'account_type' => 'donor',
                ], 200);
            } elseif ($user->account_type == 'employee') {
                return response()->json([
                    'token' => $token,
                    'account_type' => 'employee',
                ], 200);
            } elseif ($user->account_type == 'representative') {
                return response()->json([
                    'token' => $token,
                    'account_type' => 'representative',
                ], 200);
            } elseif ($user->account_type == 'admin') {
                return response()->json([
                    'token' => $token,
                    'account_type' => 'admin',
                ], 200);
            } else {
                // Handle unknown role
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function loginChild(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'user_name' => 'required',
                'password' => 'required|string'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation Error!',
                    'data' => $validate->errors(),
                ], 403);
            }

            $user = User::where('user_name', $request->user_name)->first();

            if ($user->account_type != 'child') {
                return response()->json([
                    'error' => 'account type must be child',
                ], 422);
            }

            // Check password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json([
                'token' => $token,
                'account_type' => 'child',
                'user_id' => $user->id,
                'name' => $user->name,
                'user_name' => $user->user_name,
                'image' => asset('storage/' . $user->child_profile->image),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User is logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8',
            ]);

            // Get the authenticated user
            $user = Auth::user();

            // Check if the current password matches
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'Current password is incorrect'], 400);
            }

            // Update the password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
