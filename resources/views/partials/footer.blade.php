<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <div class="logo logo-footer">
        <img class="logo-svg" src="{{ asset('assets/images/logo.png') }}" alt="LSW Cars">
      </div>
      <p class="footer-tag" style="margin-top:1rem;">{{ $company['tagline'] }}</p>
    </div>
    <div>
      <h4>Kontakt</h4>
      <p>
        {{ $company['name'] }}<br>
        {{ $company['street'] }}<br>
        {{ $company['zip'] }} {{ $company['city'] }}
      </p>
      <p>
        Tel: <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}">{{ $company['phone'] }}</a><br>
        E-Mail: <a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a>
      </p>
    </div>
    <div>
      <h4>Öffnungszeiten</h4>
      <p>Mo–Fr: 9:00 – 18:00<br>
      Sa: 10:00 – 14:00<br>
      So: geschlossen</p>
    </div>
    <div>
      <h4>Service</h4>
      <ul class="footer-links">
        <li><a href="{{ route('vehicles.index') }}">Fahrzeuge</a></li>
        <li><a href="{{ route('services') }}">Leistungen</a></li>
        <li><a href="{{ route('about') }}">Über uns</a></li>
        <li><a href="{{ route('contact') }}">Kontakt</a></li>
        <li><a href="{{ url('/admin') }}">Admin</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bar">
    <div class="container footer-bar-inner">
      <span>© {{ date('Y') }} {{ $company['name'] }}. Alle Rechte vorbehalten.</span>
      <nav class="footer-legal">
        <a href="{{ route('legal.impressum') }}">Impressum</a>
        <a href="{{ route('legal.datenschutz') }}">Datenschutz</a>
        <a href="#" id="openCookieSettings">Cookie-Einstellungen</a>
      </nav>
    </div>
  </div>
</footer>
