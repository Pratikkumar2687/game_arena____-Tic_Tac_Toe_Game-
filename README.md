<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Arena – Multiplayer Turn-Based Mini-Game Platform</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

  <!-- Header / Logo -->
  <header class="text-center py-8 bg-white shadow">
    <a href="https://laravel.com" target="_blank" class="inline-block">
      <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" 
           alt="Laravel Logo" class="w-96 mx-auto">
    </a>
    <div class="mt-4 flex justify-center space-x-4">
      <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
      </a>
      <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
      </a>
      <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
      </a>
      <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
      </a>
    </div>
  </header>

  <main class="max-w-4xl mx-auto p-6 space-y-8">

    <!-- About -->
    <section>
      <h1 class="text-3xl font-bold text-center mb-4">Game Arena – Multiplayer Turn-Based Mini-Game Platform</h1>
      <p class="text-gray-700 text-lg text-center">Game Arena is a Laravel + React application for playing turn-based mini-games (starting with Tic-Tac-Toe) against other registered users. It supports real-time updates, match invitations, inactivity reminders, and daily email summaries.</p>
    </section>

    <!-- Features -->
    <section>
      <h2 class="text-2xl font-semibold mb-4">Features</h2>
      <ul class="list-disc list-inside space-y-2 text-gray-700">
        <li><strong>Authentication & UI:</strong> Laravel Breeze with React frontend.</li>
        <li><strong>Games System:</strong> `games` table with slug-based game types (e.g., `tic-tac-toe`).</li>
        <li><strong>Matches:</strong> Player vs player matches with statuses <code>pending</code>, <code>active</code>, <code>completed</code>, <code>abandoned</code>; tracks turns via <code>current_turn_user_id</code> and JSON <code>state</code>.</li>
        <li><strong>Moves:</strong> Per-move history in `moves` table; logic handled by `TicTacToeService`.</li>
        <li><strong>Real-time Events:</strong> Match invitations, moves, and match completion via Laravel Echo + Pusher.</li>
        <li><strong>Email & Jobs:</strong> Inactive match reminders; daily summary to admin.</li>
        <li><strong>Scheduling:</strong> Console commands to check inactive matches and dispatch jobs.</li>
      </ul>
    </section>

    <!-- Tech Stack -->
    <section>
      <h2 class="text-2xl font-semibold mb-4">Tech Stack</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-gray-700">
        <div class="bg-white p-4 rounded shadow">Backend: Laravel</div>
        <div class="bg-white p-4 rounded shadow">Frontend: React (Laravel Breeze)</div>
        <div class="bg-white p-4 rounded shadow">Database: MySQL</div>
        <div class="bg-white p-4 rounded shadow">Realtime: Pusher + Laravel Echo</div>
        <div class="bg-white p-4 rounded shadow">Queue: Database driver</div>
        <div class="bg-white p-4 rounded shadow">Mail: Markdown mailables</div>
      </div>
    </section>

    <!-- Getting Started -->
    <section>
      <h2 class="text-2xl font-semibold mb-4">Getting Started</h2>

      <h3 class="text-xl font-semibold mt-4">1. Clone and Install</h3>
      <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>git clone https://github.com/&lt;your-username&gt;/game-arena.git
cd game-arena

composer install
npm install</code></pre>

      <h3 class="text-xl font-semibold mt-4">2. Environment Setup</h3>
      <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>cp .env.example .env
php artisan key:generate</code></pre>
      <p class="text-gray-700">Edit <code>.env</code> for DB, queue, broadcasting, and mail:</p>
      <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>APP_NAME="Game Arena"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=game_arena
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

QUEUE_CONNECTION=database
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1

MAIL_MAILER=log</code></pre>

      <h3 class="text-xl font-semibold mt-4">3. Database & Seeders</h3>
      <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>php artisan migrate
php artisan db:seed</code></pre>
    </section>

    <!-- Running the App -->
    <section>
      <h2 class="text-2xl font-semibold mb-4">Running the App</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <h3 class="font-semibold">Backend</h3>
          <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>php artisan serve</code></pre>
        </div>
        <div>
          <h3 class="font-semibold">Frontend</h3>
          <pre class="bg-gray-100 p-4 rounded overflow-x-auto text-sm"><code>npm run dev</code></pre>
        </div>
      </div>
      <p class="text-gray-700 mt-2">Visit <a href="http://localhost:8000" class="text-blue-600 underline">http://localhost:8000</a></p>
    </section>

    <!-- License -->
    <section class="text-center text-gray-600">
      <p>MIT License &mdash; feel free to modify for your own projects.</p>
    </section>

  </main>

</body>
</html>