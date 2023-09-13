@push('css')
    <link rel="stylesheet" href="{{ asset('css/event-detail.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/loading.js') }}"></script>
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
                            <div class="carousel">
                            <!-- スライドのリスト -->
                            <div class="contains">
                                <!-- スライドを選択するためのラジオボタンリスト。 -->
                                <!-- 数は増減しても構わないです。 最初は1番目を選択状態(checked)にします。-->
                                <!-- Slide各ラジオボタンに個別のidを定義して、nameはすべて同じ値にします。 -->
                                <input class="slide_select" type="radio" id="SlideA" name="slide_check" checked />
                                <input class="slide_select" type="radio" id="SlideB" name="slide_check" />
                                <input class="slide_select" type="radio" id="SlideC" name="slide_check" />
                                <input class="slide_select" type="radio" id="SlideD" name="slide_check" />
                                <input class="slide_select" type="radio" id="SlideE" name="slide_check" />
                                <!-- スライド -->
                                <!-- 上のラジオボックスと同じ数だけ記述します。-->
                                <div class="slide">
                                <!-- スライドの前へ、次へとスクロールさせるボタン -->
                                <div class="scroll_controler">
                                    <!-- 前へボタン forで戻る先のラジオボタンのidを書きます。-->
                                    <!-- 先頭要素なので、最後のスライドのidである"SlideE"を書いています。 -->
                                    <label class="scroll_button scroll_prev" for="SlideE"></label>
                                    <!-- 次へボタン 上と同様にforで進む先のラジオボタンのidを書きます。-->
                                    <!-- 進み先は2番目の要素なので、2番目のスライドのid"SlideB"を書いています。 -->
                                    <label class="scroll_button scroll_next" for="SlideB"></label>
                                </div>
                                <!-- スライドの内容（ここでは画像）を記述します。 -->
                                <!-- div要素に変えれば文字を加えることもできます。 -->
                                <img src="{{ Storage::url($event->image_path_a) }}">
                                </div>
                                <!-- スライド（2番目）内容は1個めと同じ -->
                                <div class="slide">
                                <div class="controler_scroll">
                                    <label class="scroll_button scroll_prev" for="SlideA"></label>
                                    <label class="scroll_button scroll_next" for="SlideC"></label>
                                </div>
                                <img src="{{ Storage::url($event->image_path_b) }}">
                                </div>
                                <!-- スライド（3番目）内容は1個めと同じ -->
                                <div class="slide">
                                <div class="controler_scroll">
                                    <label class="scroll_button scroll_prev" for="SlideB"></label>
                                    <label class="scroll_button scroll_next" for="SlideD"></label>
                                </div>
                                <img src="{{ Storage::url($event->image_path_c) }}">
                                </div>
                                <!-- スライド（4番目）内容は1個めと同じ -->
                                <div class="slide">
                                <div class="controler_scroll">
                                    <label class="scroll_button scroll_prev" for="SlideC"></label>
                                    <label class="scroll_button scroll_next" for="SlideE"></label>
                                </div>
                                <img src="{{ Storage::url($event->image_path_d) }}">
                                </div>
                                <!-- スライド（5番目）内容は1個めと同じ -->
                                <div class="slide">
                                <div class="controler_scroll">
                                    <label class="scroll_button scroll_prev" for="SlideD"></label>
                                    <label class="scroll_button scroll_next" for="SlideA"></label>
                                </div>
                                <img src="{{ Storage::url($event->image_path_e) }}">
                                </div>
                                <!-- スライド移動用ボタン -->
                                <div class="move_controler">
                                <!-- 1個目のスライドのボタン -->
                                <!-- 一番上のラジオボタンの1個目のスライドのid”A”をforに定義します-->
                                <label class="button_move" for="SlideA"></label>
                                <label class="button_move" for="SlideB"></label>
                                <label class="button_move" for="SlideC"></label>
                                <label class="button_move" for="SlideD"></label>
                                <label class="button_move" for="SlideE"></label>
                                </div>
                            </div>
                            </div>

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
                                                <th>{{ __('Participation condition') }}</th>
                                                <td>{{ $event->participation_condition }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Category') }}</th>
                                                <td>{{ $category_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Tag') }}</th>
                                                <td>{{ $event->tag }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('External link') }}</th>
                                                <td><a
                                                        href="{{ $event->external_link }}">{{ __('External link') }}</a>
                                                </td>
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
                                    <x-user-info id="{{ $participant->login_id }}" name="{{ $participant->name }}"
                                        path="{{ $participant->profile_photo_path }}" />
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
                                    <form action="{{ route('event.change.status', ['event_id' => $event->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="user_id" value="{{ $participant->user_id }}">
                                        <input type="hidden" name="status_change_token"
                                            value="{{ session('status_change_token') }}">

                                        <x-user-info id="{{ $participant->login_id }}"
                                            name="{{ trans('status.' . $participant->status) . ' - ' . $participant->name }}"
                                            path="{{ $participant->profile_photo_path }}" />

                                        <x-secondary-button type="submit" name="status" value="approved"
                                            onclick="showLoading()">{{ __('Approve') }}</x-secondary-button>
                                        <x-secondary-button type="submit" name="status" value="rejected"
                                            onclick="showLoading()">{{ __('Reject') }}</x-secondary-button>
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
                        <x-danger-button
                            onclick="return showConfirmation('{{ __('Are you sure you want to delete this event?') }}')">
                            {{ __('Event delete') }}
                        </x-danger-button>
                    </form>
                @else
                    @if ($is_join)
                        <!-- 未参加の場合 -->
                        <form method="post" action="{{ route('event.join.request', ['event_id' => $event->id]) }}"
                            class="mt-6 space-y-6" enctype="multipart/form-data">
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

                                <x-danger-button
                                    onclick="return showConfirmation('{{ __('Are you sure you want to cancel this event?') }}')">
                                    {{ __('Cancel Join') }}
                                </x-danger-button>

                            </form>
                        @endif
                    @endif
                @endif

                <x-status-modal />
            @else
                <p>Event not found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
