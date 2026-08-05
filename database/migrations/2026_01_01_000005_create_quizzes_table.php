<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('note_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->integer('total_questions')->default(0);
            $table->string('difficulty', 20)->default('medium'); // easy, medium, hard
            $table->decimal('score', 5, 2)->nullable(); // Percentage score e.g. 85.50
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->json('options'); // array of choices
            $table->string('correct_answer');
            $table->string('user_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
