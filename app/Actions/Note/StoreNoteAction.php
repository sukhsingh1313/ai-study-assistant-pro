<?php

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StoreNoteAction
{
    /**
     * Execute storing a new note with optional attachment.
     *
     * @param int $userId
     * @param array $data
     * @param UploadedFile|null $file
     * @return Note
     */
    public function execute(int $userId, array $data, ?UploadedFile $file = null): Note
    {
        $filePath = null;
        $fileType = 'text';

        if ($file) {
            $filePath = $file->store('notes', 'public');
            $fileType = strtolower($file->getClientOriginalExtension());
        }

        $tagsArray = [];
        if (!empty($data['tags'])) {
            $tagsArray = array_map('trim', explode(',', $data['tags']));
        }

        $wordCount = str_word_count(strip_tags($data['content'] ?? ''));

        return Note::create([
            'user_id' => $userId,
            'subject_id' => $data['subject_id'] ?? null,
            'category' => $data['category'] ?? null,
            'tags' => $tagsArray,
            'title' => $data['title'],
            'content' => $data['content'],
            'raw_file_path' => $filePath,
            'file_type' => $fileType,
            'word_count' => $wordCount,
        ]);
    }
}
