<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Tic-Tac-Toe',
            'slug' => 'tic-tac-toe',
        ];
    }
}