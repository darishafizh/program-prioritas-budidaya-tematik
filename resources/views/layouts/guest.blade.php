<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Survey Budidaya Tematik') }} - Login</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
</body>

</html>