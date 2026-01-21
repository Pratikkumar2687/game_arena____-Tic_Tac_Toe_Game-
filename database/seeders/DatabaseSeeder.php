<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create test users
        User::factory(5)->create();

        // Create Tic-Tac-Toe game
        Game::create([
            'name' => 'Tic-Tac-Toe',
            'slug' => 'tic-tac-toe',
        ]);
    }
}
