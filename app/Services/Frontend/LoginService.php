<?php

namespace App\Services\Frontend;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\Frontend\CartService;

class LoginService
{
    protected $cartService;
    /**
     * Create a new class instance.
     */
    public function __construct(CartService $cartService)
    {
        //
        $this->cartService = $cartService;
    }

    public function login(
        array $data
    ) {
        $login = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => 1,
        ]);

        if (!$login) {
            throw new Exception(
                'Invalid email or password.'
            );
        }

        request()
            ->session()
            ->regenerate();

        /*
        | Move Session Cart
        */
        $this->cartService->moveSessionCartToDatabase();

        return true;
    }

    /*
    | Logout User
    */
    public function logout()
    {
        Auth::logout();

        request()
            ->session()
            ->invalidate();

        request()
            ->session()
            ->regenerateToken();

        return true;
    }
}
