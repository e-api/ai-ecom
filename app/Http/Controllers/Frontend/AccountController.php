<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function edit()
    {
        // Get the current logged-in customer 
        $user = auth()->user();
        // Load the account details page
        return view('frontend.account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Validate customer information
        $validated = $request->validate([
            'name'=>[
                'required',
                'string',
                'max:255',
            ],
            'phone'=>[
                'required',
                'string',
                'max:20',
            ]
        ]);
        // Get the currently logged-in customer
        $user = $request->user();

        //Update customer information
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Account details updated successfully!'
        ]);
    } 
}
