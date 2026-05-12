@php $page = $page ?? ''; @endphp
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a class="logo" href="{{ route('home') }}" aria-label="LSW Cars Startseite">
      <img class="logo-svg" src="{{ asset('assets/images/logo.png') }}" alt="LSW Cars">
    </a>

    <nav class="nav-main" aria-label="Hauptnavigation">
      <a href="{{ route('home') }}"        class="{{ $page === 'home'        ? 'active' : '' }}">Start</a>
      <a href="{{ route('vehicles.index') }}" class="{{ $page === 'fahrzeuge'  ? 'active' : '' }}">Fahrzeuge</a>
      <a href="{{ route('services') }}"    class="{{ $page === 'leistungen'  ? 'active' : '' }}">Leistungen</a>
      <a href="{{ route('about') }}"       class="{{ $page === 'ueber'       ? 'active' : '' }}">Über uns</a>
      <a href="{{ route('contact') }}"     class="{{ $page === 'kontakt'     ? 'active' : '' }}">Kontakt</a>
    </nav>

    <a href="{{ route('contact') }}" class="btn btn-primary btn-cta">Termin vereinbaren</a>

    <button class="nav-toggle" id="navToggle" aria-label="Menü" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
