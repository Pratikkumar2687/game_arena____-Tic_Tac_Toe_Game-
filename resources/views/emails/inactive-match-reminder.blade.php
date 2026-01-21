@component('mail::message')
# Your Match is Waiting!

You have an inactive match that hasn't had a move in over 10 minutes.

**Game:** {{ $match->game->name }}
**Opponent:**
{{ $match->current_turn_user_id === $match->player_one_id ? $match->playerTwo->name : $match->playerOne->name }}

@component('mail::button', ['url' => url('/matches/' . $match->id)])
Continue Playing
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent