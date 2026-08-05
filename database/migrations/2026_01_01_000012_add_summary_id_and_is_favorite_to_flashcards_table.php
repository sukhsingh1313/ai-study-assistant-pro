<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->foreignId('summary_id')->nullable()->after('note_id')->constrained()->onDelete('set null');
            $table->boolean('is_favorite')->default(false)->after('difficulty_level');
        });
    }

    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropForeign(['summary_id']);
            $table->dropColumn(['summary_id', 'is_favorite']);
        });
    }
};
