@extends('layouts.app')
@section('page', 'fahrzeuge')
@section('title', $vehicle->titel)
@section('meta_description', $vehicle->titel.' – '.$vehicle->preis_formatiert.', '.$vehicle->km_formatiert.', EZ '.$vehicle->erstzulassung_formatiert.', '.$vehicle->kraftstoff.'. Jetzt bei '.$company['name'].' anfragen.')
@section('canonical', route('vehicles.show', $vehicle))
@section('og_type', 'product')
@section('og_image', $vehicle->firstImageUrl() ?: asset('assets/images/logo.png'))
@section('twitter_card', 'summary_large_image')

@push('seo')
@include('partials.jsonld-vehicle')
@endpush

@section('content')

@php $images = $vehicle->images; @endphp

<div class="container section" style="padding-top:2rem;">
  <x-breadcrumbs :items="[
      ['label' => 'Start', 'url' => route('home')],
      ['label' => 'Fahrzeuge', 'url' => route('vehicles.index')],
      ['label' => $vehicle->titel],
  ]" />

  <div class="detail-grid">
    <div>
      <div class="gallery">
        <div class="gallery-main">
          @if($images->count())
            <img src="{{ $images->first()->url }}" alt="{{ $vehicle->titel }} – {{ $vehicle->marke }} {{ $vehicle->modell }}">
          @endif
        </div>
        @if($images->count() > 1)
          <div class="gallery-thumbs">
            @foreach($images as $i => $img)
              <button type="button" class="{{ $i===0 ? 'active' : '' }}">
                <img src="{{ $img->url }}" alt="{{ $vehicle->titel }} – Bild {{ $i + 1 }}">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <h2 style="margin-top:2.5rem;">Beschreibung</h2>
      <p style="white-space:pre-line;color:var(--c-text);">{{ $vehicle->beschreibung ?: 'Keine Beschreibung vorhanden.' }}</p>

      <h2 style="margin-top:2.5rem;">Fahrzeugdaten</h2>
      <table class="specs-table">
        <tr><th>Marke / Modell</th><td>{{ $vehicle->marke }} {{ $vehicle->modell }}</td></tr>
        <tr><th>Erstzulassung</th><td>{{ $vehicle->erstzulassung_formatiert }}</td></tr>
        <tr><th>Kilometerstand</th><td>{{ $vehicle->km_formatiert }}</td></tr>
        <tr><th>Kraftstoff</th><td>{{ $vehicle->kraftstoff ?: '–' }}</td></tr>
        <tr><th>Getriebe</th><td>{{ $vehicle->getriebe ?: '–' }}</td></tr>
        <tr><th>Leistung</th><td>{{ $vehicle->leistung_kw ? $vehicle->leistung_kw.' kW' : '' }} {{ $vehicle->leistung_ps ? '('.$vehicle->leistung_ps.' PS)' : '–' }}</td></tr>
        @if($vehicle->hubraum)<tr><th>Hubraum</th><td>{{ number_format($vehicle->hubraum, 0, ',', '.') }} cm³</td></tr>@endif
        <tr><th>Farbe</th><td>{{ $vehicle->farbe ?: '–' }}</td></tr>
        @if($vehicle->karosserie)<tr><th>Karosserie</th><td>{{ $vehicle->karosserie }}</td></tr>@endif
        @if($vehicle->tueren)<tr><th>Türen</th><td>{{ $vehicle->tueren }}</td></tr>@endif
        @if($vehicle->sitze)<tr><th>Sitze</th><td>{{ $vehicle->sitze }}</td></tr>@endif
        <tr><th>Zustand</th><td>{{ $vehicle->zustand }}</td></tr>
        @if($vehicle->hu)<tr><th>HU</th><td>{{ $vehicle->hu }}</td></tr>@endif
        @if($vehicle->anzahl_halter)<tr><th>Halter</th><td>{{ $vehicle->anzahl_halter }}</td></tr>@endif
      </table>

      @php $equipment = $vehicle->ausstattungsListe(); @endphp
      @if(count($equipment))
        <h2 style="margin-top:2.5rem;">Ausstattung</h2>
        <ul class="equip-grid" style="padding:0;">
          @foreach($equipment as $eq)<li>{{ $eq }}</li>@endforeach
        </ul>
      @endif
    </div>

    <aside class="detail-card">
      <span class="brand-tag">{{ $vehicle->marke }}</span>
      <h1>{{ $vehicle->titel }}</h1>
      <div class="detail-price">{{ $vehicle->preis_formatiert }}</div>
      <div class="detail-price-note">Preis inkl. MwSt., ausweisbar</div>

      <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}" class="btn btn-primary btn-block btn-lg">📞 {{ $company['phone'] }}</a>
      <a href="#kontaktForm" class="btn btn-dark btn-block">Nachricht senden</a>

      <hr style="border:none;border-top:1px solid var(--c-border);margin:1.5rem 0;">

      <h3 style="font-size:1rem;">Schnellüberblick</h3>
      <ul style="list-style:none;padding:0;margin:0;display:grid;gap:.6rem;font-size:.95rem;">
        <li>📅 Erstzulassung: <strong>{{ $vehicle->erstzulassung_formatiert }}</strong></li>
        <li>🛣️ Kilometerstand: <strong>{{ $vehicle->km_formatiert }}</strong></li>
        <li>⛽ Kraftstoff: <strong>{{ $vehicle->kraftstoff ?: '–' }}</strong></li>
        <li>⚙️ Getriebe: <strong>{{ $vehicle->getriebe ?: '–' }}</strong></li>
      </ul>
    </aside>
  </div>

  <section id="kontaktForm" class="section" style="padding:4rem 0 0;">
    <div style="max-width:780px;margin:0 auto;background:#fff;border:1px solid var(--c-border);border-radius:var(--radius-lg);padding:2.5rem;">
      <h2 style="margin-bottom:.5em;">Interesse an diesem Fahrzeug?</h2>
      <p style="margin-bottom:1.5rem;">Senden Sie uns eine Nachricht – wir melden uns schnellstmöglich bei Ihnen zurück.</p>

      @if(session('contact_success'))
        <div class="alert alert-success">Vielen Dank! Ihre Nachricht wurde gesendet. Wir melden uns zeitnah.</div>
      @endif
      @if($errors->any())
        <div class="alert alert-error">
          @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
        </div>
      @endif

      <form method="post" action="{{ route('contact.store') }}" class="form-stack">
        @csrf
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
        <div class="form-row">
          <div><label for="k_name">Name *</label><input type="text" id="k_name" name="name" required value="{{ old('name') }}"></div>
          <div><label for="k_email">E-Mail *</label><input type="email" id="k_email" name="email" required value="{{ old('email') }}"></div>
        </div>
        <div><label for="k_tel">Telefon</label><input type="tel" id="k_tel" name="telefon" value="{{ old('telefon') }}"></div>
        <div><label for="k_msg">Nachricht *</label><textarea id="k_msg" name="nachricht" required>{{ old('nachricht', 'Ich interessiere mich für: '.$vehicle->titel) }}</textarea></div>
        <label class="checkbox-row">
          <input type="checkbox" name="datenschutz" value="1" required>
          <span>Ich habe die <a href="{{ route('legal.datenschutz') }}" target="_blank">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zu. *</span>
        </label>
        <button class="btn btn-primary btn-lg" type="submit">Nachricht senden</button>
      </form>
    </div>
  </section>
</div>

@endsection
