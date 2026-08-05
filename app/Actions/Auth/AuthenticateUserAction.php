<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateUserAction
{
    /**
     * Authenticate user credentials and regenerate session.
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return bool
     * @throws ValidationException
     */
    public function execute(string $email, string $password, bool $remember = false): bool
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        request()->session()->regenerate();

        return true;
    }
}
