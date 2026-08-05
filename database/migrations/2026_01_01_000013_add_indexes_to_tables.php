<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
            $table->index('category');
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->index(['user_id', 'is_completed']);
            $table->index('difficulty');
        });

        Schema::table('flashcards', function (Blueprint $table) {
            $table->index(['user_id', 'is_favorite']);
        });

        Schema::table('study_plans', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
        });

        Schema::table('reminders', function (Blueprint $table) {
            $table->index(['user_id', 'is_completed']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['category']);
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_completed']);
            $table->dropIndex(['difficulty']);
        });

        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_favorite']);
        });

        Schema::table('study_plans', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('reminders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_completed']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'read_at']);
        });
    }
};
