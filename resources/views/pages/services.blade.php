@extends('layouts.app')
@section('page', 'leistungen')
@section('title', 'Leistungen')
@section('meta_description', 'Unsere Leistungen: Fahrzeugverkauf, Ankauf, Finanzierung und Inzahlungnahme bei '.$company['name'].' in '.$company['city'].'.')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Unsere Leistungen</h1>
    <p>Vom Fahrzeugverkauf bis zur professionellen Aufbereitung – alles aus einer Hand.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="gold-divider"><span>Premium Service</span></div>

    <div class="service-grid" style="margin-top:1rem;">
      <article class="service-card" id="innenreinigung">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 4l-8 8v6h6l8-8-6-6z"/><path d="M9 9l3 3"/></svg>
        </div>
        <h3>Professionelle Innenreinigung</h3>
        <p>Tiefenreinigung Ihres Fahrzeuginnenraums mit hochwertigen Pflegemitteln – sichtbar sauberer, hygienischer Innenraum.</p>
        <ul class="equip-grid" style="text-align:left;padding:0;margin:1rem 0;display:grid;gap:.4rem;">
          <li>Sitze (Stoff oder Leder)</li>
          <li>Armaturenbrett & Konsole</li>
          <li>Teppiche & Fußmatten</li>
          <li>Türverkleidungen & Dachhimmel</li>
          <li>Fenster innen</li>
        </ul>
        <div class="price-tag">ab 50 €<br><small>Auf Wunsch mit Außenwäsche</small></div>
        <a href="{{ route('contact') }}" class="btn btn-primary mt-2">Termin buchen</a>
      </article>

      <article class="service-card" id="ankauf">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18v10H3z"/><path d="M7 11h4M7 14h2M14 11h3M14 14h3"/></svg>
        </div>
        <h3>Fahrzeug-Ankauf</h3>
        <p>Sie möchten Ihr Fahrzeug verkaufen? Wir machen Ihnen ein verbindliches Angebot innerhalb von 24 Stunden.</p>
        <ul class="equip-grid" style="text-align:left;padding:0;margin:1rem 0;display:grid;gap:.4rem;">
          <li>Faire Marktbewertung</li>
          <li>Auch Unfall- oder Defektfahrzeuge</li>
          <li>Sofortige Bezahlung</li>
          <li>Komplette Abwicklung übernehmen wir</li>
        </ul>
        <div class="price-tag">Bestpreis-Garantie</div>
        <a href="{{ route('contact') }}" class="btn btn-primary mt-2">Angebot anfordern</a>
      </article>

      <article class="service-card" id="verkauf">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M9 12h6M12 9v6"/></svg>
        </div>
        <h3>Fahrzeug-Vermittlung & Verkauf</h3>
        <p>Geprüfte Premium-Gebrauchtwagen aus unserem Bestand – sorgfältig ausgewählt und voll dokumentiert.</p>
        <ul class="equip-grid" style="text-align:left;padding:0;margin:1rem 0;display:grid;gap:.4rem;">
          <li>Komplette Wartungshistorie</li>
          <li>Probefahrt jederzeit möglich</li>
          <li>Finanzierung auf Wunsch</li>
          <li>Garantieoptionen verfügbar</li>
        </ul>
        <div class="price-tag">Top-Auswahl</div>
        <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-2">Fahrzeuge ansehen</a>
      </article>
    </div>

    <div style="margin-top:4rem;text-align:center;background:linear-gradient(135deg, rgba(201,162,74,.08), transparent);border:1px solid var(--c-border-2);border-radius:var(--radius-lg);padding:3rem 2rem;">
      <h2>Direkt anfragen</h2>
      <p style="max-width:560px;margin:0 auto 1.5rem;">Sie haben Fragen zu unseren Leistungen oder möchten direkt einen Termin vereinbaren?</p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}" class="btn btn-primary btn-lg">📞 {{ $company['phone'] }}</a>
        <a href="mailto:{{ $company['email'] }}" class="btn btn-ghost btn-lg">✉ {{ $company['email'] }}</a>
      </div>
    </div>
  </div>
</section>

@endsection
