@component('mail::message')
# Daily Match Summary

Here's your daily summary for {{ now()->format('F j, Y') }}:

**Total Matches:** {{ $totalMatches }}
**Abandoned Matches:** {{ $abandonedMatches }}

## Wins Per User
@foreach($winsPerUser as $user => $wins)
    - {{ $user }}: {{ $wins }} wins
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent