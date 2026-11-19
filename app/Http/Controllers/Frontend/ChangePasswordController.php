<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    //

    /*
    |--------------------------------------------------------------------------
    | Display Change Password Page
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        return view('frontend.account.change-password');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Customer Password
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        // Validate password information
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ]);

        // Get the currently logged-in customer
        $user = $request->user();

        // Update customer password
        $user->password = $validated['password'];

        $user->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully!'
        ]);
    }
}
