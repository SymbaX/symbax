@push('css')
    <link rel="stylesheet" href="{{ asset('css/event-detail.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/loading.js') }}"></script>
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
                                {{-- 
                                <p class="text">{{ __('Number of recruits') }}：{{ $participants->get()->Count() }}
                                    /
                                    {{ $event->number_of_recruits }}</p> --}}

                                <p class="text">
                                    {{ __('Deadline date') }}：{{ Carbon\Carbon::parse($event->deadline_date)->format('Y/m/d') }}
                                </p>

                            </div>
                        </div>
                </div>

                @if (!$is_organizer)
                    {{ __('Current your status') }}: @lang('status.' . $your_status)
                @endif

                <br /><br />
                {{ __('Participant') }}
                <ul>
                    @foreach ($participants as $participant)
                        @if ($participant->status == 'approved')
                            <li>
                                {{ $participant->name }} ({{ __('ID') }}: {{ $participant->user_id }})
                            </li>
                        @endif
                    @endforeach
                </ul>

                <br /><br />

                @if ($is_organizer)
                    <!-- 作成者のみ表示 -->
                    {{ __('All users') }}
                    <ul>
                        @foreach ($participants as $participant)
                            <li>

                                @if ($event->organizer_id === Auth::id())
                                    <form action="{{ route('event.change.status') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="user_id" value="{{ $participant->user_id }}">
                                        {{ $participant->name }} ({{ __('ID') }}:
                                        {{ $participant->user_id }},@lang('status.' . $participant->status))
                                        <button type="submit" name="status" value="approved"
                                            onclick="showLoading()">Approve</button>
                                        <button type="submit" name="status" value="rejected"
                                            onclick="showLoading()">Reject</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <a
                        href="{{ route('event.approved.users.and.organizer.only', ['id' => $event->id]) }}">{{ __('Participant only page') }}</a>
                    <br /><br />



                    <a href="{{ route('event.edit', ['id' => $event->id]) }}" class="text-blue-500 underline">
                        <x-primary-button onclick="showLoading()">{{ __('Edit event') }}</x-primary-button>
                    </a>

                    <br /><br />

                    <form method="POST" action="{{ route('event.delete', ['id' => $event->id]) }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <x-primary-button
                            onclick="return showConfirmation('{{ __('Are you sure you want to delete this event?') }}')">
                            {{ __('Event delete') }}
                        </x-primary-button>
                    </form>
                @else
                    @if ($is_join)
                        <!-- 未参加の場合 -->
                        <form method="post" action="{{ route('event.join.request') }}" class="mt-6 space-y-6"
                            enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="flex items-center gap-4">
                                <x-primary-button onclick="showLoading()">{{ __('Join request') }}</x-primary-button>
                            </div>
                        </form>
                    @else
                        <!-- 参加済みの場合 -->
                        @if ($your_status == 'approved')
                            <a
                                href="{{ route('event.approved.users.and.organizer.only', ['id' => $event->id]) }}">{{ __('Participant only page') }}</a>
                        @endif
                        <br /><br />

                        @if ($your_status != 'rejected')
                            <form action="{{ route('event.cancel-join') }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <x-primary-button onclick="showLoading()">{{ __('Cancel Join') }}</x-primary-button>
                            </form>
                        @endif
                    @endif
                @endif

                <div class="flex items-center gap-4">
                    @if (session('status') === 'join-request-event')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">{{ __('Your request to join the event has been sent.') }}</p>
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
                    @if (session('status') === 'changed-status')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('Changed participation status.') }}
                        </p>
                    @endif
                    @if (session('status') === 'not-change-status')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('It could not be changed.') }}
                        </p>
                    @endif
                    @if (session('status') === 'cancel-not-allowed')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600">
                            {{ __('Rejected events cannot be canceled.') }}
                        </p>
                    @endif
                </div>
            @else
                <p>Event not found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
