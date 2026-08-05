<?php

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateNoteAction
{
    /**
     * Update an existing note record.
     *
     * @param Note $note
     * @param array $data
     * @param UploadedFile|null $file
     * @return Note
     */
    public function execute(Note $note, array $data, ?UploadedFile $file = null): Note
    {
        $filePath = $note->raw_file_path;
        $fileType = $note->file_type;

        if ($file) {
            // Delete existing file if present
            if ($note->raw_file_path && Storage::disk('public')->exists($note->raw_file_path)) {
                Storage::disk('public')->delete($note->raw_file_path);
            }

            $filePath = $file->store('notes', 'public');
            $fileType = strtolower($file->getClientOriginalExtension());
        }

        $tagsArray = [];
        if (!empty($data['tags'])) {
            $tagsArray = array_map('trim', explode(',', $data['tags']));
        }

        $wordCount = str_word_count(strip_tags($data['content'] ?? ''));

        $note->update([
            'subject_id' => $data['subject_id'] ?? null,
            'category' => $data['category'] ?? null,
            'tags' => $tagsArray,
            'title' => $data['title'],
            'content' => $data['content'],
            'raw_file_path' => $filePath,
            'file_type' => $fileType,
            'word_count' => $wordCount,
        ]);

        return $note;
    }
}
