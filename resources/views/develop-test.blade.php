<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SymbaX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19">
</head>

<body>
    <div class="container">
        <h1>SymbaX</h1>
        <ul>
            @foreach ($colleges as $college)
            <li>{{ $college->name }}</li>
            @endforeach
        </ul>
    </div>
</body>

</html>