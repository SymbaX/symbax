<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SymbaX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('https://www.homepage-tukurikata.com/image/lion.jpg');
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
        }

        h1 {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .subtitle {
            font-size: 24px;
            font-weight: bold;
            color: #666;
            margin-bottom: 40px;
        }

        .lion-image {
            max-width: 400px;
            margin: 0 auto;
        }

        .buttons {
            margin-top: 30px;
        }

        .buttons a {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        /*
        .buttons a:hover {
            background-color: #0056b3;
        }
        */

        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            color: #999;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>SymbaX</h1>
        <p class="subtitle">Powerful Web Solutions</p>
        <!--
        <img src="https://www.homepage-tukurikata.com/image/lion.jpg" alt="Lion Image" class="lion-image">
    -->
        <p>
            Welcome to SymbaX, your partner for innovative web development.
            We combine the strength and agility of a lion with the vastness and endurance of the desert to deliver powerful web solutions that drive your business forward.
        </p>
        <div class="buttons">
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}">Go to Dashboard</a>
            @else
            <a href="{{ route('login') }}">Login</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4">Sign Up</a>
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