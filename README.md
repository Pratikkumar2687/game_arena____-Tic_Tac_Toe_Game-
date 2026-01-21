# Game Arena – Multiplayer Turn-Based Mini-Game Platform

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg"
         width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
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
</p>

# About Game Arena

Game Arena is a **Laravel + React** application for playing turn-based mini-games (starting with Tic-Tac-Toe) against other registered users. It supports **real-time updates**, **match invitations**, **inactivity reminders**, and **daily email summaries**.

---

## Features

- **Authentication & UI:** Laravel Breeze with React frontend.
- **Games System:** `games` table with slug-based game types (e.g., `tic-tac-toe`).
- **Matches:**
  - Player vs player matches with statuses `pending`, `active`, `completed`, `abandoned`.
  - Tracks turns via `current_turn_user_id` and JSON `state`.
- **Moves:** Per-move history in `moves` table; game logic handled by `TicTacToeService`.
- **Real-time Events:** Match invitations, moves, and completion via Laravel Echo + Pusher.
- **Email & Jobs:** Inactive match reminders; daily summary to admin.
- **Scheduling:** Console commands to check inactive matches and dispatch jobs.

---

## Tech Stack

| Component  | Technology |
|-----------|------------|
| Backend    | Laravel |
| Frontend   | React (Laravel Breeze) |
| Database   | MySQL |
| Realtime   | Pusher + Laravel Echo |
| Queue      | Database driver |
| Mail       | Markdown mailables |

---

## Getting Started

### 1. Clone and Install

```bash
git clone https://github.com/<your-username>/game-arena.git
cd game-arena

composer install
npm install
```

### 2. Environment Setup

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

### 3. Database & Seeders

```bash
php artisan migrate
php artisan db:seed
```

---

## API Endpoints

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

## Realtime Broadcasting

- **Channels:** `user.{userId}` and `match.{matchId}`
- **Events:** `MatchCreated`, `MoveMade`, `MatchCompleted`
- **Frontend Echo setup:** Uses Pusher keys from `.env`

---

## Queues, Jobs, and Mail

- **InactiveMatchReminderJob:** Notifies players of inactivity.
- **DailyMatchSummaryJob:** Emails admin a summary of daily matches.
- **Queue worker:**

```bash
php artisan queue:work
```

---

## Console Commands & Scheduling

- `matches:check-inactive` → Dispatches reminders.
- Scheduler in `Kernel.php` runs every 5 minutes and daily summary at 23:00.
- Run locally:

```bash
php artisan schedule:work
```

---

## Running the App

### Backend

```bash
php artisan serve
```

### Frontend

```bash
npm run dev
```

Visit [http://localhost:8000](http://localhost:8000)

---

## Deployment Notes

- Configure `.env` on server (DB, Pusher, mail, queue).
- Run `composer install --optimize-autoloader --no-dev`.
- Run `npm run build` for production assets.
- Run `php artisan migrate --force`.
- Ensure queue worker is running (`php artisan queue:work`) and scheduler is configured via cron:
  ```
  * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
  ```
- Set correct file permissions for `storage` and `bootstrap/cache`.

---

## GitHub Deployment Guide

1. Create a public repository on GitHub (`game-arena`).
2. Initialize git locally:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   ```
3. Add remote:
   ```bash
   git remote add origin https://github.com/<your-username>/game-arena.git
   ```
4. Push code:
   ```bash
   git branch -M main
   git push -u origin main
   ```

---

## License

MIT License

