@php
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'AutoDealer',
    'name' => $company['name'],
    'description' => $company['tagline'],
    'url' => url('/'),
    'logo' => asset('assets/images/logo.png'),
    'image' => asset('assets/images/logo.png'),
    'telephone' => $company['phone'],
    'email' => $company['email'],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $company['street'],
        'postalCode' => $company['zip'],
        'addressLocality' => $company['city'],
        'addressCountry' => 'DE',
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => 50.5708,
        'longitude' => 9.7367,
    ],
    'priceRange' => '€€',
    'currenciesAccepted' => 'EUR',
    'paymentAccepted' => 'Bar, Überweisung, Finanzierung',
];
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
