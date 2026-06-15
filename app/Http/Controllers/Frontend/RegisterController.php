<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Frontend\RegisterService;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(
        RegisterService $registerService
    ) {
        $this->registerService = $registerService;
    }

    /**
    | Registration Form
    |
    */
    public function register()
    {
        return view('frontend.auth.register');
    }

    /*
    | Store User
    */
    public function store(
        RegisterRequest $request
    ) {
        $this->registerService->register($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Registration completed successfully.'
        ]);
    }
}