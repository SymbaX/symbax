@push('css')
<link rel="stylesheet" href="{{ asset('css/event-detail.css') }}">
@endpush
@push('script')
<script src="{{ asset('script/loading.js') }}"></script>
@endpush

@push('meta')
    <meta property="og:title" content="{{ $event->name }}">
    <meta property="og:description" content="{{ Str::limit($event->detail, 150) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/event-titles/ogp_' . $event->id . '.png') }}">
    <meta property="og:site_name" content="SymbaX">
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->name }} | {{ __('Event details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="event-detail">
                    @if ($event)
                        <div class="event-flex">
                            <img class="event_image" src="{{ Storage::url($event->image_path) }} " alt="">

                            <div class="right">
                                <div class="scroll">
                                    <table border="1" cellspacing="0" cellpadding="5">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('Organizer') }}</th>
                                                <td>{{ $organizer_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Location') }}</th>
                                                <td>{{ $event->place }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>{{ __('Category') }}</th>
                                                <td>{{ $category_name }}</td>
                                            </tr>
                                            
                                            <tr>
                                                {{-- 参加者数 リクエスト承認待ち、承認済みの数 --}}
                                                <th>{{ __('Number of recruits') }}</th>
                                                <td>{{ $participants->filter(function ($participant) {
                                                        return $participant->status === 'approved' || $participant->status === 'pending';
                                                    })->count() }}
                                                / {{ $event->number_of_recruits }}

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <td>{{ Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Deadline date') }}</th>
                                            <td>{{ Carbon\Carbon::parse($event->deadline_date)->format('Y/m/d') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <p class="text"> {{ $detail_markdown }}</p>
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
                        <a href="{{ route('profile.show', ['login_id' => $participant->login_id]) }}">
                            <x-user-info id="{{ $participant->login_id }}" name="{{ $participant->name }}" path="{{ $participant->profile_photo_path }}" />
                        </a>
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
                        <form action="{{ route('event.change.status', ['event_id' => $event->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <input type="hidden" name="user_id" value="{{ $participant->user_id }}">
                            <input type="hidden" name="status_change_token" value="{{ session('status_change_token') }}">

                            <x-user-info id="{{ $participant->login_id }}" name="{{ trans('status.' . $participant->status) . ' - ' . $participant->name }}" path="{{ $participant->profile_photo_path }}" />

                            <x-secondary-button type="submit" name="status" value="approved" onclick="showLoading()">{{ __('Approve') }}</x-secondary-button>
                            <x-secondary-button type="submit" name="status" value="rejected" onclick="showLoading()">{{ __('Reject') }}</x-secondary-button>
                            <br /> <br />

                        </form>
                        @endif
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('event.community', ['event_id' => $event->id]) }}">
                    <x-primary-button> {{ __('Participant only page') }}</x-primary-button>
                </a>

                <br /><br />


                <a href="{{ route('event.edit', ['event_id' => $event->id]) }}" class="text-blue-500 underline">
                    <x-secondary-button>{{ __('Edit event') }}</x-secondary-button>
                </a>

                <br /><br />

                <form method="POST" action="{{ route('event.destroy', ['event_id' => $event->id]) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <x-danger-button onclick="return showConfirmation('{{ __('Are you sure you want to delete this event?') }}')">
                        {{ __('Event delete') }}
                    </x-danger-button>
                </form>
                @else
                @if ($is_join)
                <!-- 未参加の場合 -->
                <form method="post" action="{{ route('event.join.request', ['event_id' => $event->id]) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
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
                <a href="{{ route('event.community', ['event_id' => $event->id]) }}">
                    <x-primary-button> {{ __('Participant only page') }}
                    </x-primary-button>
                </a>

                <br /><br />
                @endif
                <br /><br />

                @if ($your_status != 'rejected')
                <form action="{{ route('event.cancel-join', ['event_id' => $event->id]) }}" method="POST">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <x-danger-button onclick="return showConfirmation('{{ __('Are you sure you want to cancel this event?') }}')">
                        {{ __('Cancel Join') }}
                    </x-danger-button>

                </form>
                @endif
                @endif
                @endif

                <br />
                <button id="copyShareLink">ページリンクをコピー</button>


                <x-status-modal />
                @else
                <p>Event not found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        $("#copyShareLink").click(function() {
            // コピーするリンクを指定
            var shareLink = "{{ url('/event/' . $event->id . '/share') }}";

            // テキストエリアを一時的に作成してリンクをコピー
            var $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(shareLink).select();
            document.execCommand("copy");
            $temp.remove();

            // コピー完了の通知
            alert("リンクをコピーしました!");
        });
    });
</script>