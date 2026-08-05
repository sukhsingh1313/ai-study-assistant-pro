<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateProfileAction
{
    /**
     * Update user account details and profile attributes.
     *
     * @param User $user
     * @param array $data
     * @param UploadedFile|null $avatarFile
     * @return User
     */
    public function execute(User $user, array $data, ?UploadedFile $avatarFile = null): User
    {
        // Update user core credentials
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);

        $avatarPath = $profile->avatar;
        if ($avatarFile) {
            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $avatarPath = $avatarFile->store('avatars', 'public');
        }

        $profile->update([
            'avatar' => $avatarPath,
            'phone' => $data['phone'] ?? null,
            'institution' => $data['institution'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);

        return $user;
    }
}
