@push('css')
    <link rel="stylesheet" href="{{ asset('css/event-details.css') }}">
@endpush

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
                        {{-- <p>Number of Participants: {{ $participants->Count() }}</p> --}}
                        @foreach ($participantNames as $participantName)
                            <li>{{ $participantName }}</li>
                        @endforeach

                        @if ($event->creator_id !== Auth::id() && !$participants->pluck('user_id')->contains(Auth::user()->id))
                            <form method="post" action="{{ route('event.join') }}" class="mt-6 space-y-6"
                                enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="event_id" value="{{ $event->id }}">

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Join') }}</x-primary-button>



                                </div>
                            </form>
                        @elseif ($event->creator_id === Auth::id())
                            <form method="POST" action="{{ route('event.delete', ['id' => $event->id]) }}"
                                onsubmit="return confirm('本当にこのイベントを削除しますか？');">

                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <x-primary-button>{{ __('Event delete') }}</x-primary-button>
                            </form>
                        @endif

                        @if ($participants->pluck('user_id')->contains(Auth::user()->id))
                            <form action="{{ route('cancel-join') }}" method="POST">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <x-primary-button>{{ __('Cancel Join') }}</x-primary-button>
                            </form>
                        @endif

                        <div class="flex items-center gap-4">
                            @if (session('status') === 'joined-event')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">{{ __('Joined event.') }}</p>
                            @endif
                            @if (session('status') === 'your-event-owner')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">{{ __('I cant join an event I created') }}
                                </p>
                            @endif
                            @if (session('status') === 'no-participation-slots')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">{{ __('There are no participation slots.') }}
                                </p>
                            @endif
                            @if (session('status') === 'already-joined')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">
                                    {{ __('I have already attended the event.') }}
                                </p>
                            @endif
                            @if (session('status') === 'not-joined')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">
                                    {{ __('I have not attended the event.') }}</p>
                            @endif
                            @if (session('status') === 'canceled-join')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">
                                    {{ __('I canceled my participation in the event.') }}</p>
                            @endif
                            @if (session('status') === 'cannot-delete-with-participants')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600">
                                    {{ __('Events with participants cannot be deleted.') }}</p>
                            @endif
                        </div>
                    @else
                        <p>Event not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
