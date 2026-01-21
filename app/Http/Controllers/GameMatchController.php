<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MatchCreated;
use App\Models\Game;
use App\Models\GameMatch;
use App\Services\TicTacToeService;
use Illuminate\Http\JsonResponse;

class GameMatchController extends Controller
{
    public function __construct(
        private TicTacToeService $ticTacToeService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $matches = GameMatch::where('player_one_id', $request->user()->id)
            ->orWhere('player_two_id', $request->user()->id)
            ->with(['game', 'playerOne', 'playerTwo', 'winner'])
            ->latest()
            ->get();

        return response()->json($matches);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'opponent_id' => 'required|exists:users,id|different:' . $request->user()->id,
        ]);

        $game = Game::findOrFail($validated['game_id']);

        // Initialize game state based on game type
        $initialState = match ($game->slug) {
            'tic-tac-toe' => $this->ticTacToeService->initializeBoard(),
            default => []
        };

        $match = GameMatch::create([
            'game_id' => $validated['game_id'],
            'player_one_id' => $request->user()->id,
            'player_two_id' => $validated['opponent_id'],
            'current_turn_user_id' => $request->user()->id,
            'state' => $initialState,
            'status' => 'active',
            'last_move_at' => now(),
        ]);

        $match->load(['game', 'playerOne', 'playerTwo']);

        broadcast(new MatchCreated($match))->toOthers();

        return response()->json($match, 201);
    }

    public function show(GameMatch $match): JsonResponse
    {
        $match->load(['game', 'playerOne', 'playerTwo', 'winner', 'moves.user']);
        return response()->json($match);
    }

}
