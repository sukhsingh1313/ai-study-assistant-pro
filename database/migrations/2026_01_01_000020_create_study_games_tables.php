<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('game_type', 50); // scramble, hangman, memory, rapidfire, fillblanks, match, wheel, daily
            $table->integer('score')->default(0);
            $table->integer('xp_earned')->default(0);
            $table->integer('coins_earned')->default(0);
            $table->float('accuracy_percentage')->default(100.0);
            $table->integer('duration_seconds')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'game_type']);
        });

        if (!Schema::hasColumn('user_gamifications', 'coins')) {
            Schema::table('user_gamifications', function (Blueprint $table) {
                $table->integer('coins')->default(50)->after('xp_points');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
        if (Schema::hasColumn('user_gamifications', 'coins')) {
            Schema::table('user_gamifications', function (Blueprint $table) {
                $table->dropColumn('coins');
            });
        }
    }
};
