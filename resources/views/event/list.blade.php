<link rel="stylesheet" href="{{ asset('css/list.css') }}">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if ($events->isEmpty())
                    <p>No events found.</p>
                @else
                    <div id="cardlayout-wrap">
                        <!--カードレイアウトをラッピング -->
                        @foreach ($events as $event)
                            <section class="card-list">
                                <a class="card-link" href="#">
                                    <ul>

                                        <li><a href="details/{{ $event->id }}">
                                                <class="card-title">{{ $event->name }}</class>
                                                    <figure class="card-figure"><img class="product_image mx-auto"
                                                            src="{{ Storage::url($event->product_image) }}"
                                                            alt=""></figure>
                                            </a>
                                        </li>

                                    </ul>

                                </a>
                            </section>
                        @endforeach
                    </div>
                    <!--カードレイアウトをラッピング -->
                @endif
                <br />
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
