<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | Bioflok KKP</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- KKP Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/kkp-theme.css') }}">

    <style>
        /* CAPTCHA Styles */
        .captcha-box {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            backdrop-filter: blur(4px);
        }
        .captcha-question {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex: 1;
            color: #67e8f9;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        .captcha-question svg {
            color: rgba(255, 255, 255, 0.5);
        }
        .captcha-refresh {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: all 200ms ease;
            flex-shrink: 0;
        }
        .captcha-refresh:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #67e8f9;
            transform: rotate(180deg);
        }
    </style>
</head>

<body>
    {{-- Global Page Loader --}}
    <x-page-loader />

    <div class="login-wrapper" style="--login-bg: url('{{ asset('login-bg.png') }}');">
        <div class="login-card">
            {{ $slot }}
        </div>
        <p class="login-copyright">© {{ date('Y') }} Kementerian Kelautan dan Perikanan RI</p>
    </div>
</body>

</html>