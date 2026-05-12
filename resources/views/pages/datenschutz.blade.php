@extends('layouts.app')
@section('page', 'datenschutz')
@section('title', 'Datenschutz')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Datenschutzerklärung</h1>
    <p>Informationen zur Verarbeitung Ihrer personenbezogenen Daten gemäß Art. 13 DSGVO.</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div class="prose">
    <h2>1. Verantwortlicher</h2>
    <p>
      <strong>{{ $company['name'] }}</strong><br>
      {{ $company['street'] }}, {{ $company['zip'] }} {{ $company['city'] }}<br>
      Telefon: {{ $company['phone'] }}<br>
      E-Mail: {{ $company['email'] }}
    </p>

    <h2>2. Erhebung allgemeiner Informationen</h2>
    <p>Beim Aufruf unserer Webseite werden durch unseren Webserver automatisch Informationen technischer Art erfasst (z. B. Browsertyp, Betriebssystem, Domainname, IP-Adresse, abgerufene URL, Datum und Uhrzeit). Diese Daten sind nicht bestimmten Personen zuordenbar.</p>
    <p>Rechtsgrundlage: Art. 6 Abs. 1 lit. f DSGVO.</p>

    <h2>3. Cookies</h2>
    <p>Wir setzen ausschließlich technisch notwendige Cookies. Optionale Cookies werden nur mit Ihrer ausdrücklichen Einwilligung verwendet. Sie können Ihre Einwilligung jederzeit über die <a href="#" id="reopenCookies">Cookie-Einstellungen</a> widerrufen.</p>

    <h2>4. Kontaktformulare und Fahrzeuganfragen</h2>
    <p>Wenn Sie uns über das Kontaktformular oder eine Fahrzeug-Anfrage kontaktieren, werden Ihre Angaben (Name, E-Mail, Telefon, Nachricht) zur Bearbeitung gespeichert. Daten werden nicht an Dritte weitergegeben.</p>
    <p>Rechtsgrundlage: Art. 6 Abs. 1 lit. b DSGVO.</p>

    <h2>5. Ihre Rechte</h2>
    <ul>
      <li>Auskunft (Art. 15 DSGVO)</li>
      <li>Berichtigung (Art. 16 DSGVO)</li>
      <li>Löschung (Art. 17 DSGVO)</li>
      <li>Einschränkung der Verarbeitung (Art. 18 DSGVO)</li>
      <li>Datenübertragbarkeit (Art. 20 DSGVO)</li>
      <li>Widerruf einer Einwilligung (Art. 7 Abs. 3 DSGVO)</li>
      <li>Widerspruch (Art. 21 DSGVO)</li>
    </ul>
    <p>Zur Ausübung Ihrer Rechte genügt eine formlose E-Mail an {{ $company['email'] }}.</p>

    <h2>6. Beschwerderecht</h2>
    <p>Sie haben das Recht, sich bei einer Datenschutz-Aufsichtsbehörde über die Verarbeitung Ihrer Daten zu beschweren.</p>

    <h2>7. Datensicherheit</h2>
    <p>Wir setzen technische und organisatorische Maßnahmen ein, um Ihre Daten gegen Manipulation, Verlust und unbefugten Zugriff zu schützen.</p>
  </div>
</div>

<script>
  document.getElementById('reopenCookies')?.addEventListener('click', (e) => {
    e.preventDefault();
    const b = document.getElementById('cookieBanner');
    if (b) { b.hidden = false; document.body.classList.add('cookie-banner-open'); }
  });
</script>

@endsection
