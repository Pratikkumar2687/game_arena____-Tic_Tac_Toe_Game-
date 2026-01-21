<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use App\Models\GameMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_match()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'tic-tac-toe']);

        $response = $this->actingAs($playerOne)
            ->postJson('/api/matches', [
                'game_id' => $game->id,
                'opponent_id' => $playerTwo->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'game_id',
                'player_one_id',
                'player_two_id',
                'status',
            ]);

        $this->assertDatabaseHas('game_matches', [
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_create_match_with_themselves()
    {
        $user = User::factory()->create();
        $game = Game::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/matches', [
                'game_id' => $game->id,
                'opponent_id' => $user->id,
            ]);

        $response->assertStatus(422);
    }

    public function test_player_can_make_valid_move()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'tic-tac-toe']);

        $match = GameMatch::create([
            'game_id' => $game->id,
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'current_turn_user_id' => $playerOne->id,
            'state' => ['board' => array_fill(0, 9, null), 'currentPlayer' => 'X'],
            'status' => 'active',
            'last_move_at' => now(),
        ]);

        $response = $this->actingAs($playerOne)
            ->postJson("/api/matches/{$match->id}/moves", [
                'position' => 0,
            ]);

        $response->assertStatus(200);

        $match->refresh();
        $this->assertEquals('X', $match->state['board'][0]);
        $this->assertEquals($playerTwo->id, $match->current_turn_user_id);
    }

    public function test_player_cannot_make_move_out_of_turn()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'tic-tac-toe']);

        $match = GameMatch::create([
            'game_id' => $game->id,
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'current_turn_user_id' => $playerOne->id,
            'state' => ['board' => array_fill(0, 9, null), 'currentPlayer' => 'X'],
            'status' => 'active',
            'last_move_at' => now(),
        ]);

        $response = $this->actingAs($playerTwo)
            ->postJson("/api/matches/{$match->id}/moves", [
                'position' => 0,
            ]);

        $response->assertStatus(403);
    }

    public function test_match_detects_winner()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $game = Game::factory()->create(['slug' => 'tic-tac-toe']);

        // X is about to win
        $match = GameMatch::create([
            'game_id' => $game->id,
            'player_one_id' => $playerOne->id,
            'player_two_id' => $playerTwo->id,
            'current_turn_user_id' => $playerOne->id,
            'state' => [
                'board' => ['X', 'X', null, 'O', 'O', null, null, null, null],
                'currentPlayer' => 'X'
            ],
            'status' => 'active',
            'last_move_at' => now(),
        ]);

        $response = $this->actingAs($playerOne)
            ->postJson("/api/matches/{$match->id}/moves", [
                'position' => 2,
            ]);

        $response->assertStatus(200);

        $match->refresh();
        $this->assertEquals('completed', $match->status);
        $this->assertEquals($playerOne->id, $match->winner_user_id);
    }
}