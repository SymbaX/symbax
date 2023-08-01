@push('css')
    <link rel="stylesheet" href="{{ asset('css/event-community.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/loading.js') }}"></script>
@endpush
@push('script')
    <script src="{{ asset('script/community-dropdown.js') }}"></script>
@endpush
@push('script')
    <script src="{{ asset('script/community-other.js') }}"></script>
@endpush


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('only') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event }}">

                        <div>
                            <x-textarea id="content" name="content" type="text" style="height:100px"
                                class="mt-1 block w-full" required autocomplete="off">{{ old('content', '') }}
                            </x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <x-primary-button onclick="showLoading()">{{ __('Send') }}</x-primary-button>
                    </form>

                    @forelse($topics as $topic)
                        <div class="border my-2 p-2 comment-box">
                            <div class="dropdown-container">
                                <div class="dropdown">
                                    <span class="dropdown-btn">&or;</span>
                                    <div class="dropdown-menu">
                                        <a href="#" class="copy-id-btn"
                                            data-topic-id="{{ $topic->id }}">{{ __('Copy ID') }}</a>

                                        @if ($topic->user_id == Auth::id())
                                            <form method="POST"
                                                action="{{ route('topic.delete', ['event_id' => $event, 'topic_id' => $topic->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">{{ __('Delete') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="user-info-wrapper">
                                <img id="preview"
                                    src="{{ isset($topic->user->profile_photo_path) ? Storage::url($topic->user->profile_photo_path) : asset('img/default-user.png') }}"
                                    alt="" class="w-10 h-10 rounded-full object-cover border-none bg-gray-200">
                                <div class="user-info">
                                    <div>{{ $topic->user->name }} <span
                                            class="login-id">{{ $topic->user->login_id }}</span></div>
                                </div>
                            </div>
                            <div class="comment-detail-wrapper">
                                <div class="comment-detail">
                                    @if ($topic->is_deleted == true)
                                        <div class="p-2 is_deleted"><i>{{ __('This post has been deleted') }}</i></div>
                                    @else
                                        <div class="p-2">{{ $topic->content }}</div>
                                    @endif

                                    <div class="text-secondary text-right-abs">{{ $topic->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>トピックがありません。</p>
                    @endforelse
                    {{ $topics->links('vendor.pagination.tailwind02') }}


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
