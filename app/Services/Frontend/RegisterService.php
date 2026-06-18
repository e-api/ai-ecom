<?php

namespace App\Services\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserRegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegisterService
{
    public function register(
        array $data
    ) {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 1,
        ]);

        /*
        | Send Welcome Email
        */
        try {
            Mail::to($user->email)
                ->send(
                    new UserRegisterMail($user)
                );
        } catch (\Exception $e) {
            Log::error(
                $e->getMessage()
            );
        }

        return $user;
    }
}