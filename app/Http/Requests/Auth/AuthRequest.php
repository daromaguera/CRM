<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->routeIs('user.register')) {

            $passwordValidator = Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols();

            $passwordValidator = App::environment('local') ? $passwordValidator : $passwordValidator->uncompromised();

            return [
                'firstname'         => 'required|string',
                'lastname'          => 'required|string',
                'email'             => 'required|string|unique:users',
                'company'           => 'required|string',
                'password'          => [
                    'required',
                    'confirmed',
                    $passwordValidator
                ]
            ];
        }

        return [
            'email'             => 'required|string|email',
            'password'          => 'required|string',
            'remember_me'       => 'boolean'
        ];
    }
}
