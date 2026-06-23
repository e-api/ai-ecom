<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Services\Frontend\LoginService;

class LoginController extends Controller
{
    //
    protected $loginService;

    public function __construct(
        LoginService $loginService
    ) {
        $this->loginService = $loginService;
    }

    /*
    | Login Form
    */
    public function login()
    {
        return view('frontend.auth.login');
    }

    /*
    | Login User
    */
    public function store(
        LoginRequest $request
    ) {
        try {
            $this->loginService->login($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'redirect' => url('/')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'errors' => [
                    'email' => [
                        $e->getMessage()
                    ]
                ]
            ], 422);
        }
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

        return redirect('/');
    }
}
