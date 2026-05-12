<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $company['name'] }} – {{ $company['tagline'] }}. Premium-Fahrzeuge zu fairen Preisen.">
<title>@yield('title', $company['name']) · {{ $company['name'] }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
</head>
<body data-page="@yield('page', '')">

@include('partials.header')

<main class="site-main">
    @yield('content')
</main>

@include('partials.footer')
@include('partials.whatsapp')
@include('partials.cookie-banner')

<script src="{{ asset('assets/js/main.js') }}" defer></script>
</body>
</html>
