<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('summary_id')->nullable()->after('note_id')->constrained()->onDelete('set null');
            $table->integer('timer_minutes')->default(10)->after('difficulty');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['summary_id']);
            $table->dropColumn(['summary_id', 'timer_minutes']);
        });
    }
};
