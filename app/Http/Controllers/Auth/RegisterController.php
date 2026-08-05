<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\RegisterUserAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->route('dashboard')->with('success', 'Account created successfully! Welcome to AI Study Assistant.');
    }
}
