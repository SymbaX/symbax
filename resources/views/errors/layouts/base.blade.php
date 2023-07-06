<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>
    <div class="error-wrap">
        <section>
            <h1>@yield('title')</h1>
            <p class="error-message">@yield('message')</p>
            <p class="error-detail">@yield('detail')</p>
            <div class="link">
                @yield('link')
            </div>
        </section>
    </div>
</body>

</html>
