<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'phone' => [
                'required',
                'digits:10',
                'unique:users,phone',
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
            ],
            'terms' => [
                'accepted',
            ],
        ];
    }
    
    /*
    Handle a failed validation attempt.
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422)
        );
    }
    */
}
