<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
    <link rel="icon" href="{{ asset('/favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('index.upcoming') }}">
                                <img src="{{ asset('img/logo.svg') }}" width="50" height="50">
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="container">
                <h1 class="error_title">@yield('title')</h1>
                <p class="error_message">@yield('message')</p>
                <p class="error_detail">@yield('detail')</p>

                @if (!isset($errorCode) || $errorCode != 503)
                    <a href="{{ route('welcome') }}">{{ __('Back to top page') }}</a>
                @endif

                @if (env('APP_DEBUG') == 1 && isset($exception))
                    <p class="error_debug">
                        {{ $exception->getMessage() }}
                    </p>
                @endif

                <div class="error_img">
                    @php
                        $images = ['img/errors/error01.jpeg', 'img/errors/error02.jpeg', 'img/errors/error03.jpeg', 'img/errors/error04.jpeg', 'img/errors/error05.jpeg', 'img/logo.png'];
                        $randomImage = $images[array_rand($images)];
                    @endphp

                    <img src="{{ asset($randomImage) }}" alt="dog">
                </div>
            </div>
        </main>
    </div>

</body>

</html>
