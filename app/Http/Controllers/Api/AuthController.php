<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Mail\OtpMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        $parentRole = Role::where('name', 'parent')->first();
        if ($parentRole) {
            $user->roles()->attach($parentRole->id);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('roles'),
        ], 201);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginType = filter_var($validated['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$loginType => $validated['email'], 'password' => $validated['password']])) {
            return response()->json([
                'message' => 'Invalid login credentials.',
            ], 422);
        }

        $user = User::where($loginType, $validated['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('roles'),
        ]);
    }

    /**
     * Handle generating and sending forgot password OTP.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json([
            'message' => 'Verification code sent to your email.',
        ]);
    }

    /**
     * Handle verifying forgot password OTP.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $record = DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        return response()->json([
            'message' => 'Verification code is valid.',
        ]);
    }

    /**
     * Handle password reset with OTP.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $record = DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Invalid or expired verification code.',
            ], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }
}
