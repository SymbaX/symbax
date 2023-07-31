@if ($events->isEmpty())
    <p>{{ __('No events found.') }}</p>
@else
    <div id="card-layout-wrap">
        @foreach ($events as $event)
            <section class="card-list">
                <a class="card-link" href="#">
                    <ul>
                        <li><a href="{{ route('event.show', $event->id) }}">
                                <h4 class="card-title">{{ $event->name }}</h4>
                                <figure class="card-figure"><img class="event_image mx-auto"
                                        src="{{ Storage::url($event->image_path) }}" alt="">
                                </figure>
                                <p class="card-text-tax">{{ $event->detail }}</p>
                            </a>
                        </li>
                    </ul>
                </a>
            </section>
        @endforeach
    </div>
@endif
<br />
{{ $events->links() }}
