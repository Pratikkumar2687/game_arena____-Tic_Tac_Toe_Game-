<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function index(): JsonResponse
    {
        $games = Game::all();
        return response()->json($games);
    }
}
