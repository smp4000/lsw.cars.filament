@props(['items'])

@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [],
];
foreach ($items as $i => $item) {
    $jsonLd['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $item['label'],
        'item' => $item['url'] ?? null,
    ];
}
@endphp

<nav aria-label="Breadcrumb" class="breadcrumbs">
  <ol>
    @foreach($items as $i => $item)
      <li>
        @if(isset($item['url']) && $i < count($items) - 1)
          <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
          <span aria-current="page">{{ $item['label'] }}</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
