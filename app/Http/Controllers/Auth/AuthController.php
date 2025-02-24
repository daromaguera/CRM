<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\AuthRequest;
use Wotz\VerificationCode\VerificationCode;
use App\Models\User;


class AuthController extends Controller
{
    /**
     * Create user
     */
    public function register(AuthRequest $request, AuthService $authService)
    {
        $user = $authService->addUser($request, 2); // 2 value is a role for Realtor, 1 for Admin, 3 for HomeOwners and 4 for Company users

        if (!$user->save()) return response()->json(['error' => 'Invalid Data'], 422);

        event(new Registered($user));

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'userData'      => $user,
            'accessToken'   => $token,
        ], 201);
    }

    /**
     * Login user and create token
     *
     */
    public function login(AuthRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials, $request->remember_me)) return response()->json(['message' => 'Unauthorized'], 401);

        $user = $request->user();

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'userData'      => $user,
            'accessToken'   => $token,
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Verify Email using Code
     *
     */
    public function code(Request $request)
    {
        $user = $request->user();

        $verify = VerificationCode::verify($request->code, $user->email);

        if (!$verify)
            return response([
                'message' => 'Verification Code is Incorrect'
            ], 422);

        if ($user->markEmailAsVerified())
            event(new Verified($user));

        return response()->json([
            'userData'      => $user,
        ]);
    }

    /**
     * Verify Email Code for Reset Password
     */
    public function codepassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $verify = VerificationCode::verify($request->code, $user->email);

        if (!$verify) {
            return response()->json([
                'message' => 'Verification Code is Incorrect'
            ], 422);
        }

        // Store that the user has passed verification
        $user->update(['email_verified_at' => now()]);

        return response()->json([
            'message' => 'Verification successful. Proceed with password reset.',
            'userData' => $user,
        ]);
    }
}
