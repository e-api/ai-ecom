<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    //
    /*
    |--------------------------------------------------------------------------
    | Display Delivery Addresses
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $deliveryAddresses = DeliveryAddress::where(
            'user_id',
            auth()->id()
        )
        ->orderByDesc('is_default')
        ->latest()
        ->get();

        return view(
            'frontend.delivery-addresses.index',
            compact('deliveryAddresses')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Display Store Addresses
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'max:50'
            ],
            'address' => [
                'required',
                'string',
                'max:1000'
            ],
            'city' => [
                'required',
                'string',
                'max:255'
            ],
            'state' => [
                'nullable',
                'string',
                'max:255'
            ],
            'country' => [
                'required',
                'string',
                'max:255'
            ],
            'postal_code' => [
                'nullable',
                'string',
                'max:50'
            ],
            'redirect_to' => [
                'nullable',
                'in:checkout,delivery-addresses'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Existing Addresses
        |--------------------------------------------------------------------------
        */
        $hasAddresses = DeliveryAddress::where(
            'user_id',
            auth()->id()
        )->exists();

        /*
        |--------------------------------------------------------------------------
        | Create Delivery Address
        |--------------------------------------------------------------------------
        */
        DeliveryAddress::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
            'postal_code' => $validated['postal_code'],

            /*
            |--------------------------------------------------------------------------
            | Make First Address Default
            |--------------------------------------------------------------------------
            */
            'is_default' => !$hasAddresses
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect User Based on Previous Page
        |--------------------------------------------------------------------------
        */
        if ($request->redirect_to === 'checkout') {
            return redirect()
                ->route('checkout.index')
                ->with(
                    'success',
                    'Delivery address added successfully.'
                );
        }

        return redirect()
            ->route('delivery-addresses.index')
            ->with(
                'success',
                'Delivery address added successfully.'
            );
    }
}
