@extends('layouts.app')
@section('page', 'impressum')
@section('title', 'Impressum')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Impressum</h1>
    <p>Angaben gemäß § 5 TMG</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div class="prose">
    <h2>Anbieter</h2>
    <p>
      <strong>{{ $company['name'] }}</strong><br>
      {{ $company['street'] }}<br>
      {{ $company['zip'] }} {{ $company['city'] }}
    </p>

    <h2>Vertreten durch</h2>
    <p>{{ $company['owner'] }}</p>

    <h2>Kontakt</h2>
    <p>
      Telefon: <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}">{{ $company['phone'] }}</a><br>
      E-Mail: <a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a>
    </p>

    <h2>Registereintrag</h2>
    <p>
      Registergericht: {{ $company['court'] }}<br>
      Registernummer: {{ $company['hrb'] }}
    </p>

    <h2>Umsatzsteuer-ID</h2>
    <p>{{ $company['vat'] }}</p>

    <h2>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
    <p>
      {{ $company['owner'] }}<br>
      {{ $company['street'] }}<br>
      {{ $company['zip'] }} {{ $company['city'] }}
    </p>

    <h2>EU-Streitschlichtung</h2>
    <p>
      Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
      <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr</a>.
    </p>

    <h2>Verbraucher­streit­beilegung / Universal­schlichtungs­stelle</h2>
    <p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>

    <h2>Haftung für Inhalte</h2>
    <p>Als Diensteanbieter sind wir gemäß § 7 Abs. 1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen.</p>

    <h2>Haftung für Links</h2>
    <p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.</p>

    <h2>Urheberrecht</h2>
    <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht.</p>
  </div>
</div>

@endsection
