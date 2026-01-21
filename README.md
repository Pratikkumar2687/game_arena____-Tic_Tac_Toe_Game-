Here’s your polished `README.md` ready to download and use. I cleaned up formatting, ensured it’s concise but complete, and kept all the sections you requested. You can save this as `README.md` in your project root.

````markdown
# Game Arena – Multiplayer Turn-Based Mini-Game Platform

Game Arena is a Laravel + React application for playing turn-based mini-games (starting with Tic-Tac-Toe) against other registered users. It supports real-time updates, match invitations, inactivity reminders, and daily email summaries.

---

## Features

- **Authentication & UI:** Laravel Breeze with React frontend.
- **Games System:** `games` table with slug-based game types (e.g., `tic-tac-toe`).
- **Matches:**
  - Player vs player matches with `pending`, `active`, `completed`, `abandoned` statuses.
  - Turn tracking via `current_turn_user_id`.
  - JSON `state` for game-specific data.
- **Moves:** Per-move history stored in `moves` table; logic handled by `TicTacToeService`.
- **Real-time Events:** Match invitations, moves, and match completion via Laravel Echo + Pusher.
- **Email & Jobs:** Inactive match reminders; daily summary to admin.
- **Scheduling:** Console commands to check inactive matches and dispatch jobs.

---

## Tech stack

- **Backend:** Laravel
- **Frontend:** React (via Laravel Breeze)
- **Database:** MySQL
- **Realtime:** Pusher + Laravel Echo
- **Queue:** Database driver
- **Mail:** Markdown mailables

---

## Getting started

### 1. Clone and install

```bash
git clone https://github.com/<your-username>/game-arena.git
cd game-arena

composer install
npm install
````

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for DB, queue, broadcasting, and mail:

```env
APP_NAME="Game Arena"

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

MAIL_MAILER=log
```

### 3. Database & seeders

```bash
php artisan migrate
php artisan db:seed
```

---

## Core domain structure

### Models

* **Game:** `id`, `name`, `slug`; `hasMany(Match::class)`
* **Match:** Tracks game, players, current turn, state, status, winner, moves.
* **Move:** Tracks individual moves per match.

### Game service

* **`TicTacToeService`** handles board initialization, move validation, winner/draw detection.

---

## API endpoints

All routes require `auth:sanctum`.

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/matches', [MatchController::class, 'index']);
    Route::post('/matches', [MatchController::class, 'store']);
    Route::get('/matches/{match}', [MatchController::class, 'show']);
    Route::post('/matches/{match}/moves', [MoveController::class, 'store']);
});
```

---

## Realtime broadcasting

* **Channels:** `user.{userId}` and `match.{matchId}`
* **Events:** `MatchCreated`, `MoveMade`, `MatchCompleted`
* **Frontend Echo setup:** Uses Pusher keys from `.env`

---

## Frontend (React)

* API service with `axios` for games, matches, and moves.
* Lobby page lists games, matches, and subscribes to real-time events.

---

## Queues, jobs, and mail

* **InactiveMatchReminderJob:** Notifies players of inactivity.
* **DailyMatchSummaryJob:** Emails admin a summary of daily matches.
* Run queue worker:

```bash
php artisan queue:work
```

---

## Console commands & scheduling

* `matches:check-inactive` → Dispatches reminders.
* Scheduler in `Kernel.php` runs every 5 minutes and daily summary at 23:00.
* Run locally:

```bash
php artisan schedule:work
```

---

## Running the app

```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000`.

---

## Testing

```bash
php artisan test
```

---

## Deployment notes

* Configure `.env` for server DB, Pusher, mail, queue.
* `composer install --optimize-autoloader --no-dev`
* `npm run build`
* `php artisan migrate --force`
* Ensure queue worker and scheduler cron are running.
* Set correct permissions for `storage` and `bootstrap/cache`.

---

## GitHub Deployment Guide

1. **Create repo:** GitHub → New → Public → `game-arena`.
2. **Initialize Git:** `git init` in project root.
3. **.gitignore:** Include `/vendor`, `/node_modules`, `/storage/*.key`, `.env`, `/public/build`, etc.
4. **Add & commit:** `git add . && git commit -m "Initial commit"`.
5. **Add remote:** `git remote add origin https://github.com/<username>/game-arena.git`.
6. **Push:** `git branch -M main && git push -u origin main`.

---

## License

MIT (or your preferred license)

```

---

If you want, I can also make a **slim “one-page” version** of this README specifically optimized for GitHub, highlighting only key setup, features, and deployment steps—great for public repos. Do you want me to do that?
```
