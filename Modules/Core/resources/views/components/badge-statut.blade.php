@props(['statut'])

@if($statut)
    {!! $statut->badge_html !!}
@else
    <span class="badge bg-secondary">-</span>
@endif
