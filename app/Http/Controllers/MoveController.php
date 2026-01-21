<?php

namespace App\Http\Controllers;

use App\Events\MoveMade;
use App\Events\MatchCompleted;
use App\Models\GameMatch;
use App\Models\Move;
use App\Services\TicTacToeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    public function __construct(
        private TicTacToeService $ticTacToeService
    ) {
    }

    public function store(Request $request, GameMatch $match): JsonResponse
    {
        // Validate match status
        if ($match->status !== 'active') {
            return response()->json(['message' => 'Match is not active'], 400);
        }

        // Validate turn
        if (!$match->isPlayerTurn($request->user())) {
            return response()->json(['message' => 'Not your turn'], 403);
        }

        $validated = $request->validate([
            'position' => 'required|integer|min:0|max:8',
        ]);

        try {
            // Determine player symbol
            $player = $match->player_one_id === $request->user()->id ? 'X' : 'O';

            // Make move
            $newState = $this->ticTacToeService->makeMove(
                $match->state,
                $validated['position'],
                $player
            );

            // Create move record
            $move = Move::create([
                'match_id' => $match->id,
                'user_id' => $request->user()->id,
                'move_data' => [
                    'position' => $validated['position'],
                    'player' => $player
                ],
            ]);

            // Check for winner or draw
            $winner = $this->ticTacToeService->checkWinner($newState['board']);
            $isDraw = $this->ticTacToeService->isDraw($newState['board']);

            if ($winner || $isDraw) {
                $match->update([
                    'state' => $newState,
                    'status' => 'completed',
                    'winner_user_id' => $winner
                        ? ($player === 'X'
                            ? $match->player_one_id
                            : $match->player_two_id)
                        : null,
                    'last_move_at' => now(),
                ]);

                broadcast(new MatchCompleted($match));
            } else {
                // Switch turns
                $nextPlayer = $match->current_turn_user_id === $match->player_one_id
                    ? $match->player_two_id
                    : $match->player_one_id;

                $match->update([
                    'state' => $newState,
                    'current_turn_user_id' => $nextPlayer,
                    'last_move_at' => now(),
                ]);
            }

            broadcast(new MoveMade($match, $move));

            return response()->json([
                'match' => $match->fresh(),
                'move' => $move,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}