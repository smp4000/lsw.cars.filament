@extends('layouts.app')
@section('page', 'fahrzeuge')
@section('title', 'Fahrzeuge')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Aktuelle Fahrzeuge</h1>
    <p>Stöbern Sie durch unseren aktuellen Fahrzeugbestand. Filtern Sie nach Marke, Preis und Ausstattung, um genau das passende Modell zu finden.</p>
  </div>
</header>

<div class="container">
  <form method="get" class="filter-bar">
    <div>
      <label for="marke">Marke</label>
      <select id="marke" name="marke">
        <option value="">Alle</option>
        @foreach($brands as $b)
          <option value="{{ $b }}" @selected(request('marke')===$b)>{{ $b }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label for="kraftstoff">Kraftstoff</label>
      <select id="kraftstoff" name="kraftstoff">
        <option value="">Alle</option>
        @foreach(['Benzin','Diesel','Elektro','Hybrid','LPG','CNG'] as $k)
          <option @selected(request('kraftstoff')===$k)>{{ $k }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label for="getriebe">Getriebe</label>
      <select id="getriebe" name="getriebe">
        <option value="">Alle</option>
        <option @selected(request('getriebe')==='Automatik')>Automatik</option>
        <option @selected(request('getriebe')==='Schaltgetriebe')>Schaltgetriebe</option>
      </select>
    </div>
    <div>
      <label for="karosserie">Karosserie</label>
      <select id="karosserie" name="karosserie">
        <option value="">Alle</option>
        @foreach($bodyTypes as $b)
          <option @selected(request('karosserie')===$b)>{{ $b }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label for="preis_max">Preis bis (€)</label>
      <input type="number" min="0" step="500" id="preis_max" name="preis_max" value="{{ request('preis_max') }}" placeholder="z. B. 25000">
    </div>
    <div>
      <label for="km_max">Km bis</label>
      <input type="number" min="0" step="1000" id="km_max" name="km_max" value="{{ request('km_max') }}" placeholder="z. B. 80000">
    </div>
    <div class="filter-actions">
      <button class="btn btn-primary" type="submit">Filtern</button>
      <a href="{{ route('vehicles.index') }}" class="btn btn-ghost">Reset</a>
    </div>
  </form>

  <div class="result-meta">
    <span><strong>{{ $vehicles->count() }}</strong> Fahrzeuge gefunden</span>
    <form method="get" style="display:flex;align-items:center;gap:.5rem;">
      @foreach(request()->except('sort') as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
      @endforeach
      <label for="sort" style="margin:0;">Sortieren:</label>
      <select id="sort" name="sort" onchange="this.form.submit()" style="padding:.5rem .75rem;border-radius:var(--radius);background:#fff;">
        <option value="neu"        @selected($sort==='neu')>Neueste zuerst</option>
        <option value="preis_asc"  @selected($sort==='preis_asc')>Preis aufsteigend</option>
        <option value="preis_desc" @selected($sort==='preis_desc')>Preis absteigend</option>
        <option value="km_asc"     @selected($sort==='km_asc')>Wenig Km zuerst</option>
        <option value="jahr_desc"  @selected($sort==='jahr_desc')>Neueste Erstzulassung</option>
      </select>
    </form>
  </div>

  @if($vehicles->count())
    <div class="vehicle-grid">
      @foreach($vehicles as $v)
        <x-vehicle-card :vehicle="$v" />
      @endforeach
    </div>
  @else
    <div class="empty-state">
      <h3>Keine Fahrzeuge gefunden</h3>
      <p>Mit den aktuellen Filtern wurden keine Fahrzeuge gefunden. Bitte passen Sie Ihre Auswahl an.</p>
      <a href="{{ route('vehicles.index') }}" class="btn btn-primary mt-2">Filter zurücksetzen</a>
    </div>
  @endif
</div>

@endsection
