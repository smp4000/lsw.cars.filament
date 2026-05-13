@extends('layouts.app')
@section('page', 'home')
@section('title', 'Startseite')
@section('meta_description', $company['name'].' – Ihr Autohaus in '.$company['city'].'. Premium-Gebrauchtwagen zu fairen Preisen. Persönliche Beratung, Probefahrt und Finanzierung.')

@push('seo')
@include('partials.jsonld-autodealer')
@endpush

@section('content')

<section class="hero">
  <div class="container hero-inner">
    <div>
      <span class="hero-eyebrow">Premium-Fahrzeuge · Sofort verfügbar</span>
      <h1>Ihr Traumauto bei<br><em>LSW Cars</em></h1>
      <p class="lead">
        Geprüfte Gebrauchtwagen und Premium-Modelle – mit voller Transparenz,
        fairen Preisen und persönlicher Beratung. Wir bringen Sie ans Steuer.
      </p>
      <div class="hero-actions">
        <a href="{{ route('vehicles.index') }}" class="btn btn-primary btn-lg">Jetzt Fahrzeuge entdecken</a>
        <a href="{{ route('contact') }}" class="btn btn-ghost btn-lg" style="color:#fff;border-color:rgba(255,255,255,.25)">Beratung anfragen</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><strong>{{ $totalCars }}+</strong><span>Fahrzeuge im Bestand</span></div>
        <div class="stat"><strong>15</strong><span>Jahre Erfahrung</span></div>
        <div class="stat"><strong>4.9★</strong><span>Kundenbewertung</span></div>
      </div>
    </div>
    <div class="hero-visual" aria-hidden="true">
      <img src="{{ asset('assets/images/amg-gts.jpg') }}" alt="Mercedes-AMG GT S">
    </div>
  </div>
</section>

<div class="container">
  <form class="search-card" action="{{ route('vehicles.index') }}" method="get">
    <div>
      <label for="f_marke">Marke</label>
      <select name="marke" id="f_marke">
        <option value="">Alle Marken</option>
        @foreach($brands as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach
      </select>
    </div>
    <div>
      <label for="f_kraft">Kraftstoff</label>
      <select name="kraftstoff" id="f_kraft">
        <option value="">Alle</option>
        @foreach(['Benzin','Diesel','Elektro','Hybrid'] as $k)<option>{{ $k }}</option>@endforeach
      </select>
    </div>
    <div>
      <label for="f_max">Preis bis</label>
      <select name="preis_max" id="f_max">
        <option value="">Beliebig</option>
        <option value="10000">10.000 €</option>
        <option value="20000">20.000 €</option>
        <option value="30000">30.000 €</option>
        <option value="50000">50.000 €</option>
        <option value="100000">100.000 €</option>
      </select>
    </div>
    <div>
      <label for="f_km">Km bis</label>
      <select name="km_max" id="f_km">
        <option value="">Beliebig</option>
        <option value="20000">20.000</option>
        <option value="50000">50.000</option>
        <option value="100000">100.000</option>
        <option value="150000">150.000</option>
      </select>
    </div>
    <button class="btn btn-primary btn-lg" type="submit">Suchen</button>
  </form>
</div>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Ausgewählte Fahrzeuge</span>
      <h2>Unsere Highlights</h2>
      <p>Eine Auswahl aus unserem aktuellen Angebot – sorgfältig geprüft und sofort fahrbereit.</p>
    </div>

    @if($featured->count())
      <div class="vehicle-grid">
        @foreach($featured as $v)
          <x-vehicle-card :vehicle="$v" />
        @endforeach
      </div>
      <div class="text-center mt-3">
        <a href="{{ route('vehicles.index') }}" class="btn btn-dark btn-lg">Alle Fahrzeuge ansehen →</a>
      </div>
    @else
      <div class="empty-state">
        <h3>Aktuell sind keine Fahrzeuge online.</h3>
        <p>Bitte schauen Sie bald wieder vorbei oder kontaktieren Sie uns direkt.</p>
        <a href="{{ route('contact') }}" class="btn btn-primary mt-2">Kontakt aufnehmen</a>
      </div>
    @endif
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Unsere Leistungen</span>
      <h2>Mehr als nur Autohandel</h2>
      <p>Vom Premium-Gebrauchten bis zur professionellen Innenreinigung – wir kümmern uns um Ihr Fahrzeug.</p>
    </div>

    <div class="service-grid">
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l3 3 3-3-3-3-3 3z"/><path d="M14 5l4 4-8 8H6v-4l8-8z"/></svg>
        </div>
        <h3>Professionelle Innenreinigung</h3>
        <p>Gründliche Aufbereitung Ihres Fahrzeuginnenraums – Sitze, Cockpit, Teppiche und Verkleidungen.</p>
        <div class="price-tag">ab 50 €<br><small>Auf Wunsch mit Außenwäsche</small></div>
        <a href="{{ route('services') }}" class="btn btn-ghost mt-2">Mehr erfahren →</a>
      </div>
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14M5 7h14M5 12h14"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
        <h3>Fahrzeug-Verkauf</h3>
        <p>Geprüfte Premium-Fahrzeuge zu fairen Preisen – mit voller Transparenz und persönlicher Beratung.</p>
        <div class="price-tag">Top-Auswahl</div>
        <a href="{{ route('vehicles.index') }}" class="btn btn-ghost mt-2">Zum Bestand →</a>
      </div>
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        </div>
        <h3>Ankauf Ihres Fahrzeugs</h3>
        <p>Schnell, fair und unkompliziert – verbindliches Angebot innerhalb von 24 Stunden.</p>
        <div class="price-tag">Bestpreis</div>
        <a href="{{ route('contact') }}" class="btn btn-ghost mt-2">Angebot anfordern →</a>
      </div>
    </div>
  </div>
</section>

@endsection
