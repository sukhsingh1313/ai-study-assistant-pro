<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add SoftDeletes & Pinned flag to notes
        Schema::table('notes', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('is_pinned')->default(false)->after('category');
        });

        // Add SoftDeletes to summaries
        Schema::table('summaries', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add SoftDeletes to quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add SoftDeletes to flashcards
        Schema::table('flashcards', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Create audit_logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // Create login_histories table
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('device', 50)->nullable();
            $table->timestamp('login_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('audit_logs');

        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('is_pinned');
        });
    }
};
