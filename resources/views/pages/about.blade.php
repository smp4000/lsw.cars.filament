@extends('layouts.app')
@section('page', 'ueber')
@section('title', 'Über uns')

@section('content')

<header class="page-header">
  <div class="container">
    <h1>Über LSW Cars</h1>
    <p>Ihr verlässlicher Partner für Premium-Fahrzeuge – mit Leidenschaft, Erfahrung und persönlichem Service.</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div class="prose">
    <p>Bei <strong>{{ $company['name'] }}</strong> dreht sich alles um eines: Sie und Ihr Traumauto. Seit unserer Gründung stehen wir für sorgfältig geprüfte Fahrzeuge, faire Preise und eine Beratung, die diesen Namen verdient.</p>

    <h2>Unsere Werte</h2>
    <ul>
      <li><strong>Transparenz.</strong> Jedes Fahrzeug wird offen und ehrlich präsentiert – inklusive aller relevanten Details.</li>
      <li><strong>Qualität.</strong> Wir führen ausschließlich technisch geprüfte und gepflegte Fahrzeuge.</li>
      <li><strong>Service.</strong> Von der Probefahrt bis zur Übergabe sind wir persönlich für Sie da.</li>
      <li><strong>Vertrauen.</strong> Langfristige Kundenbeziehungen statt schneller Abschlüsse.</li>
    </ul>

    <h2>Unser Angebot</h2>
    <p>Wir bieten Ihnen ein sorgfältig kuratiertes Portfolio aus Premium-Fahrzeugen – von kompakten Stadtflitzern bis zu komfortablen Limousinen und sportlichen SUVs. Zusätzlich übernehmen wir die Inzahlungnahme Ihres Altfahrzeugs und vermitteln auf Wunsch passende Finanzierungs- und Leasinglösungen.</p>

    <h2>Besuchen Sie uns</h2>
    <p>Kommen Sie vorbei und überzeugen Sie sich vor Ort. Wir freuen uns auf Ihren Besuch.</p>

    <p style="margin-top:2rem;">
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">Termin vereinbaren</a>
    </p>
  </div>
</div>

@endsection
