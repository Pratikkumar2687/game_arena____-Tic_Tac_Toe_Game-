<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_one_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player_two_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('current_turn_user_id')->nullable()->constrained('users');
            $table->json('state');
            $table->enum('status', ['pending', 'active', 'completed', 'abandoned'])->default('pending');
            $table->foreignId('winner_user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_matches');
    }
};
