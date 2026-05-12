@extends('layouts.app')
@section('page', 'kontakt')
@section('title', 'Kontakt')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Kontakt</h1>
    <p>Wir freuen uns auf Ihre Nachricht – persönlich, telefonisch oder per E-Mail.</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:3rem;">
    <div>
      <h2>Schreiben Sie uns</h2>

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
        <div class="form-row">
          <div><label for="name">Name *</label><input type="text" id="name" name="name" required value="{{ old('name') }}"></div>
          <div><label for="email">E-Mail *</label><input type="email" id="email" name="email" required value="{{ old('email') }}"></div>
        </div>
        <div><label for="tel">Telefon</label><input type="tel" id="tel" name="telefon" value="{{ old('telefon') }}"></div>
        <div><label for="msg">Nachricht *</label><textarea id="msg" name="nachricht" required>{{ old('nachricht') }}</textarea></div>
        <label class="checkbox-row">
          <input type="checkbox" name="datenschutz" value="1" required>
          <span>Ich habe die <a href="{{ route('legal.datenschutz') }}" target="_blank">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zu. *</span>
        </label>
        <button class="btn btn-primary btn-lg" type="submit">Nachricht senden</button>
      </form>
    </div>

    <aside style="background:var(--c-surface-2);border:1px solid var(--c-border);border-radius:var(--radius-lg);padding:2rem;height:fit-content;">
      <h3>So erreichen Sie uns</h3>
      <p style="color:var(--c-text);">
        <strong>{{ $company['name'] }}</strong><br>
        {{ $company['street'] }}<br>
        {{ $company['zip'] }} {{ $company['city'] }}
      </p>
      <p style="color:var(--c-text);">
        📞 <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}">{{ $company['phone'] }}</a><br>
        ✉ <a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a>
      </p>
      <h4 style="margin-top:1.5rem;">Öffnungszeiten</h4>
      <p style="color:var(--c-text);">
        Mo–Fr: 9:00 – 18:00 Uhr<br>
        Sa: 10:00 – 14:00 Uhr<br>
        So: geschlossen
      </p>
    </aside>
  </div>
</div>

@endsection
