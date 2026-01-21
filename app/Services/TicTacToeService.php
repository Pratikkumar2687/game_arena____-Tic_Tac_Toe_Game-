<?php

namespace App\Services;

class TicTacToeService
{
    public function initializeBoard(): array
    {
        return [
            'board' => array_fill(0, 9, null),
            'currentPlayer' => 'X',
        ];
    }

    public function makeMove(array $state, int $position, string $player): array
    {
        if ($state['board'][$position] !== null) {
            throw new \Exception('Position already occupied');
        }

        $state['board'][$position] = $player;
        $state['currentPlayer'] = $player === 'X' ? 'O' : 'X';

        return $state;
    }

    public function checkWinner(array $board): ?string
    {
        $winPatterns = [
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8], // Rows
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8], // Columns
            [0, 4, 8],
            [2, 4, 6]             // Diagonals
        ];

        foreach ($winPatterns as $pattern) {
            [$a, $b, $c] = $pattern;
            if ($board[$a] && $board[$a] === $board[$b] && $board[$a] === $board[$c]) {
                return $board[$a];
            }
        }

        return null;
    }

    public function isDraw(array $board): bool
    {
        return !in_array(null, $board, true) && $this->checkWinner($board) === null;
    }
}