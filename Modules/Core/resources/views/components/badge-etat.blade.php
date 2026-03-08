@props(['etat'])

@if($etat)
    {!! $etat->badge_html !!}
@else
    <span class="badge bg-secondary">-</span>
@endif
