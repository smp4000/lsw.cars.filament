@php
$images = $vehicle->images->map(fn($img) => $img->url)->toArray();
$ez = null;
if ($vehicle->erstzulassung && preg_match('/^(\d{4})-(\d{2})/', $vehicle->erstzulassung, $m)) {
    $ez = $m[1].'-'.$m[2];
}
$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Car',
    'name' => $vehicle->titel,
    'brand' => ['@type' => 'Brand', 'name' => $vehicle->marke],
    'model' => $vehicle->modell,
    'vehicleConfiguration' => $vehicle->titel,
    'color' => $vehicle->farbe,
    'numberOfDoors' => $vehicle->tueren,
    'seatingCapacity' => $vehicle->sitze,
    'vehicleTransmission' => $vehicle->getriebe,
    'fuelType' => $vehicle->kraftstoff,
    'mileageFromOdometer' => [
        '@type' => 'QuantitativeValue',
        'value' => $vehicle->kilometerstand,
        'unitCode' => 'KMT',
    ],
    'vehicleEngine' => [
        '@type' => 'EngineSpecification',
        'enginePower' => [
            '@type' => 'QuantitativeValue',
            'value' => $vehicle->leistung_kw,
            'unitCode' => 'KWT',
        ],
    ],
    'vehicleInteriorColor' => null,
    'bodyType' => $vehicle->karosserie,
    'itemCondition' => $vehicle->zustand === 'Neu' ? 'https://schema.org/NewCondition' : 'https://schema.org/UsedCondition',
    'offers' => [
        '@type' => 'Offer',
        'price' => number_format((float) $vehicle->preis, 2, '.', ''),
        'priceCurrency' => 'EUR',
        'availability' => $vehicle->verkauft
            ? 'https://schema.org/SoldOut'
            : 'https://schema.org/InStock',
        'seller' => [
            '@type' => 'AutoDealer',
            'name' => $company['name'],
            'telephone' => $company['phone'],
        ],
    ],
    'image' => $images,
    'url' => route('vehicles.show', $vehicle),
];
if ($ez) $schema['dateVehicleFirstRegistered'] = $ez;
if ($vehicle->beschreibung) $schema['description'] = \Illuminate\Support\Str::limit(strip_tags($vehicle->beschreibung), 300);
$schema = array_filter($schema, fn($v) => $v !== null);
$schema['offers'] = array_filter($schema['offers'], fn($v) => $v !== null);
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
