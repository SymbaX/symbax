<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="{{ $event->name }}">
    <meta property="og:description" content="{{ Str::limit($event->detail, 150) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/event-titles/ogp_' . $event->id . '.png') }}">
    <meta property="og:site_name" content="SymbaX">

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body
    onLoad="setTimeout(() => { window.location.href = '{{ route('event.show', ['event_id' => $event->id]) }}'; }, 8000)">

    <header>
        <h1>{{ $event->name }}</h1>
    </header>

    <main>
        <section>

            <div class="flex justify-center">
                <a href="{{ route('event.show', ['event_id' => $event->id]) }}">
                    <img src="{{ asset('storage/event-titles/ogp_' . $event->id . '.png') }}" alt="title image"
                        class="w-3/4 sm:w-1/2 md:w-1/3 lg:w-1/4"><br />
                    {{ __('You will be automatically redirected to the event details page.') }} <br />
                    {{ __('If it does not move after waiting for a while, click here.') }}
                </a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 SymbaX</p>
    </footer>

</body>

</html>
