<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Survey Budidaya Tematik') }} - Login</title>

    <!-- Fonts - Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- KKP Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/kkp-theme.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            {{ $slot }}
        </div>
        <p class="login-copyright">© {{ date('Y') }} Kementerian Kelautan dan Perikanan RI</p>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
