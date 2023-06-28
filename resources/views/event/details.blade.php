<link rel="stylesheet" href="{{ asset('css/event-details.css') }}">
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if ($event)
                        <h1>イベント名：{{ $event->name }}</h1><br /><br />
                        <div class="event-details">
                            {{ $detail_markdown }}
                        </div><br /><br />
                        <p>カテゴリー：{{ $event->category }}</p><br />
                        <p>タグ：{{ $event->tag }}</p><br />
                        <p>参加条件：{{ $event->conditions_of_participation }}</p><br />
                        <p>外部リンク：{{ $event->extarnal_links }}</p><br />
                        <p>開催日：{{ $event->datetime }}</p><br />
                        <p>開催場所：{{ $event->place }}</p><br />
                        <p>人数：{{ $event->number_of_people }}</p><br />
                        <p>画像パス：{{ $event->product_image }}</p><br />
                        <img class="product_image" src="{{ Storage::url($event->product_image) }}" alt=""
                            width="150px" height="100px">

                        {{ $event->id }}

                        <form method="post" action="{{ route('event.join') }}" class="mt-6 space-y-6"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Join') }}</x-primary-button>

                                @if (session('status') === 'joined-event')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600">{{ __('Joined event.') }}</p>
                                @endif
                            </div>
                        </form>
                    @else
                        <p>Event not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
