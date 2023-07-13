<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SymbaX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>

<body>
    <div class="container">
        <h1>SymbaX</h1>
        <p class="subtitle">{{ __('Event sharing platform') }}</p>
        <p>
            {{ __('Welcome') }}
        </p>


        <div class="buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/upcoming') }}">{{ __('Start') }}</a>
                @else
                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4">{{ __('Sign Up') }}</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2023 SymbaX. All rights reserved.</p>
    </div>
</body>

</html>
