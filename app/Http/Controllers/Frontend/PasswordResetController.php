<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Throwable;

class PasswordResetController extends Controller
{
    // Display Forgot Password Form
    public function create()
    {
        return view('frontend.auth.forgot-password');
    }

    // Send Password Reset Email
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {

            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Password Reset Status', [
                'status' => $status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'If an account exists with this email address, a password reset link has been sent.'
            ]);

        } catch (Throwable $e) {

            Log::error('Password Reset Email Failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to send the password reset email. Please try again.'
            ], 500);
        }
    }

    // Display Reset Password Form
    public function edit(
        Request $request,
        string $token
    ) {
        return view(
            'frontend.auth.reset-password',
            [
                'token' => $token,
                'email' => $request->query('email'),
            ]
        );
    }

    // Update Password
    public function update(Request $request)
    {
        $request->validate([
            'token' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, string $password) {

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => 'Your password has been reset successfully!'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'This password reset link is invalid or has expired.'
        ], 422);
    }
}