<?php

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Support\Facades\Storage;

class DeleteNoteAction
{
    /**
     * Delete note record and associated stored attachment.
     *
     * @param Note $note
     * @return bool
     */
    public function execute(Note $note): bool
    {
        if ($note->raw_file_path && Storage::disk('public')->exists($note->raw_file_path)) {
            Storage::disk('public')->delete($note->raw_file_path);
        }

        return (bool) $note->delete();
    }
}
