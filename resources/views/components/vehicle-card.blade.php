@props(['vehicle'])

@php
  $img = $vehicle->firstImageUrl();
@endphp

<a class="vehicle-card" href="{{ route('vehicles.show', $vehicle) }}">
  <div class="v-img">
    @if($img)
      <img src="{{ $img }}" alt="{{ $vehicle->titel }}" loading="lazy">
    @else
      <svg class="placeholder-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M10 38l4-12a6 6 0 0 1 5.7-4.2h24.6A6 6 0 0 1 50 26l4 12v10a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4H18v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V38z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
        <circle cx="20" cy="40" r="3" fill="currentColor"/>
        <circle cx="44" cy="40" r="3" fill="currentColor"/>
      </svg>
    @endif
    @if($vehicle->verkauft)
      <span class="badge badge-sold">Verkauft</span>
    @endif
  </div>
  <div class="vehicle-body">
    <span class="brand">{{ $vehicle->marke }}</span>
    <h3>{{ $vehicle->titel }}</h3>
    <div class="vehicle-specs">
      <span>{!! $svgCalendar ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>' !!}{{ $vehicle->erstzulassung_formatiert }}</span>
      <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 21l4-18M20 21l-4-18M12 4v2M12 10v2M12 16v2"/></svg>{{ $vehicle->km_formatiert }}</span>
      <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 21V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v16M5 21h11M9 7h4M16 11l3 1v6a2 2 0 0 1-4 0"/></svg>{{ $vehicle->kraftstoff ?? '–' }}</span>
      <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/></svg>{{ $vehicle->leistung_ps ? $vehicle->leistung_ps.' PS' : '–' }}</span>
    </div>
    <div class="vehicle-foot">
      <span class="vehicle-price">{{ $vehicle->preis_formatiert }}</span>
      <span class="btn btn-ghost">Details →</span>
    </div>
  </div>
</a>
