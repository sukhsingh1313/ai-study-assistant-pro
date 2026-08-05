<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Actions\Profile\UpdateProfileAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show form for editing user profile.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $user->load('profile');

        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile settings in storage.
     */
    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): RedirectResponse
    {
        $user = Auth::user();
        $action->execute($user, $request->validated(), $request->file('avatar'));

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
