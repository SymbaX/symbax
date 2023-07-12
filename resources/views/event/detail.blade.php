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
                <div class="event-details">
                    @if ($event)
                        <div class="flex">
                            <img class="event_image" src="{{ Storage::url($event->image_path) }} " alt="">

                            <div class="right">
                                <p class="title"> {{ Carbon\Carbon::parse($event->date)->format('Y/m/d') }} |
                                    {{ $event->name }} </p>
                                <p id="organizer_name">{{ __('Organizer') }}：{{ $organizer_name }}</p>
                                <br>
                                <p class="text"> {{ $detail_markdown }}</p>
                                <p class="text">{{ __('Location') }}：{{ $event->place }}</p>
                                <p class="text">
                                    {{ __('Participation condition') }}：{{ $event->participation_condition }}</p>
                                <p class="text">{{ __('Category') }}：{{ $event->category }}</p>
                                <p class="text">{{ __('Tag') }}：{{ $event->tag }}</p>
                                <a href="{{ $event->external_link }}">{{ __('External link') }}</a>

                                <p class="text">{{ __('Number of recruits') }}：{{ $participants->get()->Count() }}
                                    /
                                    {{ $event->number_of_recruits }}</p>
                                <p class="text">
                                    {{ __('Deadline date') }}：{{ Carbon\Carbon::parse($event->deadline_date)->format('Y/m/d') }}
                                </p>

                            </div>
                        </div>
                </div>

                @foreach ($participant_names as $userId => $participantName)
                    <li>{{ $participantName }} (ID: {{ $userId }})</li>
                @endforeach

                @if ($is_organizer)
                    <!-- 作成者のみ表示 -->
                    <a href="{{ route('event.edit', ['id' => $event->id]) }}" class="text-blue-500 underline">
                        <x-primary-button>{{ __('Edit event') }}</x-primary-button>
                    </a>

                    <br /><br />

                    <form method="POST" action="{{ route('event.delete', ['id' => $event->id]) }}"
                        onsubmit="return confirm( '{{ __('Are you sure you want to delete this event?') }}' );">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <x-primary-button>{{ __('Event delete') }}</x-primary-button>
                    </form>
                @else
                    @if ($is_join)
                        <!-- 未参加の場合 -->
                        <form method="post" action="{{ route('event.join') }}" class="mt-6 space-y-6"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Join') }}</x-primary-button>
                            </div>
                        </form>
                    @else
                        <!-- 参加済みの場合 -->

                        <form action="{{ route('event.cancel-join') }}" method="POST">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <x-primary-button>{{ __('Cancel Join') }}</x-primary-button>
                        </form>

                        <p class="text">{{ __('Current Status') }}:
                            {{ $participants->where('user_id', Auth::id())->first()->status }}</p>
                    @endif
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
                            {{ __('I have not attended the event.') }}
                        </p>
                    @endif
                    @if (session('status') === 'canceled-join')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('I canceled my participation in the event.') }}
                        </p>
                    @endif
                    @if (session('status') === 'cannot-delete-with-participants')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('Events with participants cannot be deleted.') }}
                        </p>
                    @endif
                    @if (session('status') === 'event-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('Updated event information.') }}
                        </p>
                    @endif
                    @if (session('status') === 'unauthorized')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('This request is invalid.') }}
                        </p>
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
