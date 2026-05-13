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
            <span class="gallery-counter">1 / {{ $images->count() }}</span>
            @if($images->count() > 1)
              <button type="button" class="gallery-nav gallery-nav--prev" aria-label="Vorheriges Bild">&#10094;</button>
              <button type="button" class="gallery-nav gallery-nav--next" aria-label="Nächstes Bild">&#10095;</button>
            @endif
            <div class="gallery-actions">
              @if($images->count() > 1)
                <button type="button" class="gallery-action-btn" id="galleryAllBtn">&#9783; Alle Bilder</button>
              @endif
              <button type="button" class="gallery-action-btn" id="galleryZoomBtn">&#128269; Vergrößern</button>
            </div>
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

      <div class="quickfacts">
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V4M4 12l8-8 8 8"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Kilometerstand</span>
            <strong>{{ $vehicle->km_formatiert }}</strong>
          </div>
        </div>
        @if($vehicle->leistung_kw)
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Leistung</span>
            <strong>{{ $vehicle->leistung_kw }} kW ({{ $vehicle->leistung_ps }} PS)</strong>
          </div>
        </div>
        @endif
        @if($vehicle->kraftstoff)
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22V6a2 2 0 012-2h6a2 2 0 012 2v16"/><path d="M13 10h4a2 2 0 012 2v2"/><path d="M19 14v6a2 2 0 01-2 2"/><circle cx="19" cy="6" r="2"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Kraftstoffart</span>
            <strong>{{ $vehicle->kraftstoff }}</strong>
          </div>
        </div>
        @endif
        @if($vehicle->getriebe)
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="6" cy="18" r="2"/><path d="M6 8v10M18 8v4a4 4 0 01-4 4H6"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Getriebe</span>
            <strong>{{ $vehicle->getriebe }}</strong>
          </div>
        </div>
        @endif
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Erstzulassung</span>
            <strong>{{ $vehicle->erstzulassung_formatiert }}</strong>
          </div>
        </div>
        @if($vehicle->anzahl_halter)
        <div class="quickfact">
          <svg class="quickfact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <div class="quickfact-text">
            <span class="quickfact-label">Fahrzeughalter</span>
            <strong>{{ $vehicle->anzahl_halter }}</strong>
          </div>
        </div>
        @endif
      </div>

      <h2 style="margin-top:2.5rem;">Beschreibung</h2>
      <p style="white-space:pre-line;color:var(--c-text);">{{ $vehicle->beschreibung ?: 'Keine Beschreibung vorhanden.' }}</p>

      <div class="techdata">
        <h2 class="techdata-title">Technische Daten</h2>
        <table class="techdata-table">
          <tbody class="techdata-visible">
            <tr><th>Fahrzeugzustand</th><td>{{ $vehicle->zustand }}@if($vehicle->unfallfrei), Unfallfrei @endif</td></tr>
            @if($vehicle->karosserie)<tr><th>Kategorie</th><td>{{ $vehicle->karosserie }}</td></tr>@endif
            @if($vehicle->baureihe)<tr><th>Baureihe</th><td>{{ $vehicle->baureihe }}</td></tr>@endif
            @if($vehicle->ausstattungslinie)<tr><th>Ausstattungslinie</th><td>{{ $vehicle->ausstattungslinie }}</td></tr>@endif
            @if($vehicle->herkunft)<tr><th>Herkunft</th><td>{{ $vehicle->herkunft }}</td></tr>@endif
            <tr><th>Kilometerstand</th><td>{{ $vehicle->km_formatiert }}</td></tr>
            @if($vehicle->hubraum)<tr><th>Hubraum</th><td>{{ number_format($vehicle->hubraum, 0, ',', '.') }} cm³</td></tr>@endif
            <tr><th>Leistung</th><td>{{ $vehicle->leistung_kw ? $vehicle->leistung_kw.' kW ('.$vehicle->leistung_ps.' PS)' : '–' }}</td></tr>
          </tbody>
          <tbody class="techdata-more" hidden>
            @if($vehicle->antriebsart)<tr><th>Antriebsart</th><td>{{ $vehicle->antriebsart }}</td></tr>@endif
            <tr><th>Kraftstoffart</th><td>{{ $vehicle->kraftstoff ?: '–' }}</td></tr>
            @if($vehicle->energieverbrauch)<tr><th>Verbrauch (kombiniert)</th><td>{{ str_replace('.', ',', $vehicle->energieverbrauch) }} l/100km</td></tr>@endif
            @if($vehicle->verbrauch_innerorts)<tr><th>Verbrauch (innerorts)</th><td>{{ str_replace('.', ',', $vehicle->verbrauch_innerorts) }} l/100km</td></tr>@endif
            @if($vehicle->verbrauch_ausserorts)<tr><th>Verbrauch (außerorts)</th><td>{{ str_replace('.', ',', $vehicle->verbrauch_ausserorts) }} l/100km</td></tr>@endif
            @if($vehicle->co2_emissionen)<tr><th>CO₂-Emissionen (komb.)</th><td>{{ str_replace('.', ',', $vehicle->co2_emissionen) }} g/km</td></tr>@endif
            @if($vehicle->co2_klasse)<tr><th>CO₂-Klasse</th><td>{{ $vehicle->co2_klasse }}</td></tr>@endif
            @if($vehicle->energiekosten)<tr><th>Energiekosten/Jahr</th><td>{{ number_format($vehicle->energiekosten, 0, ',', '.') }} €</td></tr>@endif
            @if($vehicle->sitze)<tr><th>Anzahl Sitzplätze</th><td>{{ $vehicle->sitze }}</td></tr>@endif
            @if($vehicle->tueren)<tr><th>Anzahl der Türen</th><td>{{ $vehicle->tueren }}@if($vehicle->schiebetuer), Schiebetür @endif</td></tr>@endif
            <tr><th>Getriebe</th><td>{{ $vehicle->getriebe ?: '–' }}</td></tr>
            @if($vehicle->schadstoffklasse)<tr><th>Schadstoffklasse</th><td>{{ $vehicle->schadstoffklasse }}</td></tr>@endif
            @if($vehicle->umweltplakette)<tr><th>Umweltplakette</th><td>{{ $vehicle->umweltplakette }}</td></tr>@endif
            <tr><th>Erstzulassung</th><td>{{ $vehicle->erstzulassung_formatiert }}</td></tr>
            @if($vehicle->produktionsdatum)<tr><th>Produktionsdatum</th><td>{{ $vehicle->produktionsdatum }}</td></tr>@endif
            @if($vehicle->anzahl_halter)<tr><th>Anzahl der Fahrzeughalter</th><td>{{ $vehicle->anzahl_halter }}</td></tr>@endif
            @if($vehicle->hu)<tr><th>HU</th><td>{{ $vehicle->hu }}</td></tr>@endif
            @if($vehicle->klimatisierung)<tr><th>Klimatisierung</th><td>{{ $vehicle->klimatisierung }}</td></tr>@endif
            @if($vehicle->einparkhilfe_detail)<tr><th>Einparkhilfe</th><td>{{ $vehicle->einparkhilfe_detail }}</td></tr>@endif
            @if($vehicle->airbags)<tr><th>Airbags</th><td>{{ $vehicle->airbags }}</td></tr>@endif
            @if($vehicle->farbe_hersteller)<tr><th>Farbe (Hersteller)</th><td>{{ $vehicle->farbe_hersteller }}</td></tr>@endif
            <tr><th>Farbe</th><td>{{ $vehicle->farbe ?: '–' }}@if($vehicle->metallic) (Metallic)@endif @if($vehicle->lackierung) – {{ $vehicle->lackierung }}@endif</td></tr>
            @if($vehicle->innenausstattung)<tr><th>Innenausstattung</th><td>{{ $vehicle->innenausstattung }}</td></tr>@endif
            @if($vehicle->anhaengelast_gebremst)<tr><th>Anhängelast gebremst</th><td>{{ number_format($vehicle->anhaengelast_gebremst, 0, ',', '.') }} kg</td></tr>@endif
            @if($vehicle->anhaengelast_ungebremst)<tr><th>Anhängelast ungebremst</th><td>{{ number_format($vehicle->anhaengelast_ungebremst, 0, ',', '.') }} kg</td></tr>@endif
            @if($vehicle->gewicht)<tr><th>Gewicht</th><td>{{ number_format($vehicle->gewicht, 0, ',', '.') }} kg</td></tr>@endif
            @if($vehicle->letzte_wartung_datum)<tr><th>Letzte Wartung (Datum)</th><td>{{ $vehicle->letzte_wartung_datum }}</td></tr>@endif
            @if($vehicle->letzte_wartung_km)<tr><th>Letzte Wartung (km)</th><td>{{ number_format($vehicle->letzte_wartung_km, 0, ',', '.') }} km</td></tr>@endif
            @if($vehicle->zylinder)<tr><th>Zylinder</th><td>{{ $vehicle->zylinder }}</td></tr>@endif
            @if($vehicle->tankgroesse)<tr><th>Tankgröße</th><td>{{ $vehicle->tankgroesse }} l</td></tr>@endif
            @if($vehicle->nichtraucher)<tr><th>Nichtraucherfahrzeug</th><td>Ja</td></tr>@endif
            @if($vehicle->scheckheftgepflegt)<tr><th>Scheckheftgepflegt</th><td>Ja</td></tr>@endif
            @if($vehicle->garantie)<tr><th>Garantie</th><td>{{ $vehicle->garantie }}</td></tr>@endif
            @if($vehicle->fahrgestellnummer)<tr><th>Fahrgestellnummer</th><td>{{ $vehicle->fahrgestellnummer }}</td></tr>@endif
          </tbody>
        </table>
        <button type="button" class="techdata-toggle" id="techdataToggle">Mehr anzeigen</button>
      </div>

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
      <div class="detail-price-note">{{ $vehicle->mwst ?: 'Preis inkl. MwSt., ausweisbar' }}</div>

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

@if($images->count())
<div class="lightbox-overlay" id="lightbox" hidden>
  <button type="button" class="lightbox-close" aria-label="Schließen">&times;</button>
  <div class="lightbox-content">
    <img src="" alt="{{ $vehicle->titel }}">
    <span class="lightbox-counter"></span>
    <button type="button" class="gallery-nav gallery-nav--prev" aria-label="Vorheriges Bild">&#10094;</button>
    <button type="button" class="gallery-nav gallery-nav--next" aria-label="Nächstes Bild">&#10095;</button>
  </div>
</div>

<div class="allimages-overlay" id="allImages" hidden>
  <div class="allimages-header">
    <h2>Alle Bilder ({{ $images->count() }})</h2>
    <button type="button" class="lightbox-close" aria-label="Schließen">&times;</button>
  </div>
  <div class="allimages-grid">
    @foreach($images as $i => $img)
      <button type="button" data-index="{{ $i }}">
        <img src="{{ $img->url }}" alt="{{ $vehicle->titel }} – Bild {{ $i + 1 }}" loading="lazy">
      </button>
    @endforeach
  </div>
</div>
@endif

@endsection
