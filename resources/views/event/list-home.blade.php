@push('css')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/event-carousel.js') }}"></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if ($events->currentPage() === 1)
                    <h3 class="title-h3">{{ __('New events') }}</h3>
                    @if ($events->isEmpty())
                        <p>{{ __('No events found.') }}</p>
                    @else
                        <div id="carousel-container">
                            <button id="carousel-button-prev" class="carousel-button">{{ __('Prev') }}</button>
                            <div id="highlighted-event-wrap">
                                @foreach ($newest_events as $newest_event)
                                    <section class="highlighted-event-card">
                                        <a class="card-link" href="#">
                                            <ul>
                                                <li><a href="{{ route('event.show', $newest_event->id) }}">
                                                        <h3 class="highlighted-event-title">{{ $newest_event->name }}
                                                        </h3>
                                                        <figure class="card-figure"><img class="event_image mx-auto"
                                                                src="{{ Storage::url($newest_event->image_path) }}"
                                                                alt="">
                                                        </figure>
                                                        <p class="highlighted-event-text">
                                                            {{ Carbon\Carbon::parse($newest_event->date)->format('Y/m/d') }}
                                                            {{ $newest_event->detail }}
                                                        </p>
                                                    </a></li>
                                            </ul>
                                        </a>
                                    </section>
                                @endforeach
                            </div>
                            <button id="carousel-button-next" class="carousel-button">{{ __('Next') }}</button>
                        </div>
                    @endif
                @endif

                <br />
                <h3 class="title-h3">{{ __('Upcoming events') }}</h3>

                @include('event.partials.list')

                <x-status-modal />

            </div>
        </div>
</x-app-layout>
