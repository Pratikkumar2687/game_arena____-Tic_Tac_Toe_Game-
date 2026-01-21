<?php

namespace Tests\Unit;

use App\Services\TicTacToeService;
use PHPUnit\Framework\TestCase;

class TicTacToeServiceTest extends TestCase
{
    private TicTacToeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TicTacToeService();
    }

    public function test_initializes_empty_board()
    {
        $state = $this->service->initializeBoard();

        $this->assertCount(9, $state['board']);
        $this->assertEquals('X', $state['currentPlayer']);
        $this->assertTrue(in_array(null, $state['board']));
    }

    public function test_detects_horizontal_win()
    {
        $board = ['X', 'X', 'X', 'O', 'O', null, null, null, null];

        $winner = $this->service->checkWinner($board);

        $this->assertEquals('X', $winner);
    }

    public function test_detects_vertical_win()
    {
        $board = ['X', 'O', null, 'X', 'O', null, 'X', null, null];

        $winner = $this->service->checkWinner($board);

        $this->assertEquals('X', $winner);
    }

    public function test_detects_diagonal_win()
    {
        $board = ['X', 'O', 'O', null, 'X', null, null, null, 'X'];

        $winner = $this->service->checkWinner($board);

        $this->assertEquals('X', $winner);
    }

    public function test_detects_draw()
    {
        $board = ['X', 'O', 'X', 'X', 'O', 'X', 'O', 'X', 'O'];

        $isDraw = $this->service->isDraw($board);

        $this->assertTrue($isDraw);
    }

    public function test_prevents_move_on_occupied_position()
    {
        $this->expectException(\Exception::class);

        $state = ['board' => ['X', null, null, null, null, null, null, null, null], 'currentPlayer' => 'O'];

        $this->service->makeMove($state, 0, 'O');
    }
}