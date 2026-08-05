<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('second_brains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->json('weak_topics')->nullable();
            $table->json('strong_topics')->nullable();
            $table->json('recommendations')->nullable();
            $table->string('learning_pattern', 50)->default('Visual & Practice');
            $table->timestamps();
        });

        Schema::create('knowledge_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->string('chapter')->nullable();
            $table->string('topic');
            $table->text('formula')->nullable();
            $table->text('definition')->nullable();
            $table->foreignId('note_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('quiz_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('coding_snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('language', 30);
            $table->string('title');
            $table->longText('code');
            $table->longText('ai_explanation')->nullable();
            $table->longText('ai_bugs')->nullable();
            $table->longText('ai_optimization')->nullable();
            $table->timestamps();
        });

        Schema::create('whiteboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('canvas_data')->nullable();
            $table->timestamps();
        });

        Schema::create('research_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('authors')->nullable();
            $table->string('year', 10)->nullable();
            $table->string('citation_style', 20)->default('APA');
            $table->text('formatted_citation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_references');
        Schema::dropIfExists('whiteboards');
        Schema::dropIfExists('coding_snippets');
        Schema::dropIfExists('knowledge_nodes');
        Schema::dropIfExists('second_brains');
    }
};
