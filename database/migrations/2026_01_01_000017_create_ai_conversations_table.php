<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->default('AI Tutor Session');
            $table->string('prompt_type', 50)->default('general'); // chat, beginner, teacher, rewrite, mindmap, viva, exercises
            $table->text('prompt');
            $table->longText('response');
            $table->string('model_used', 50)->default('gemini-2.0-flash');
            $table->integer('tokens_estimated')->default(0);
            $table->unsignedTinyInteger('rating')->nullable(); // 1 to 5 stars
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
