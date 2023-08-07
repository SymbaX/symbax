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
                            <div id="content-count">0 / 300</div>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <x-primary-button class="send-button" onclick="showLoading()"
                            disabled>{{ __('Post') }}</x-primary-button>

                    </form>

                    @forelse($topics as $topic)
                        <div class="border my-2 p-2 comment-box">
                            <div class="dropdown-container">
                                <div class="dropdown">
                                    <span class="dropdown-btn">&or;</span>
                                    <div class="dropdown-menu">
                                        <a href="#" class="copy-id-btn"
                                            data-topic-id="{{ $topic->id }}">{{ __('Copy ID') }}</a>

                                        @if ($topic->user_id == Auth::id() and $topic->is_deleted == false)
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




                            <button onclick="toggleEmojiPicker(this)">😀</button>

                            <div class="emoji-picker" style="display: none;">
                                <form action="{{ route('reactions.store', ['topic' => $topic->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" name="emoji" value="😀">😀</button>
                                    <button type="submit" name="emoji" value="😂">😂</button>
                                    <button type="submit" name="emoji" value="😍">😍</button>
                                    <button type="submit" name="emoji" value="😊">😊</button>
                                    <button type="submit" name="emoji" value="👍">👍</button>
                                </form>

                                <button onclick="toggleMoreEmojis(this)">...</button>


                                @php
                                    $emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓', '🧐', '😕', '😟', '🙁', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾'];
                                @endphp


                                <div class="more-emojis" style="display: none;">
                                    <form action="{{ route('reactions.store', ['topic' => $topic->id]) }}"
                                        method="post">
                                        @csrf
                                        @foreach ($emojis as $emoji)
                                            <button type="submit" name="emoji"
                                                value="{{ $emoji }}">{{ $emoji }}</button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>


                            <div class="reaction-counts">
                                @foreach ($emojis as $emoji)
                                    @php
                                        $count = \App\Models\Reaction::getCountForTopic($topic->id, $emoji);
                                        $hasReacted = \App\Models\Reaction::hasReacted(Auth::id(), $topic->id, $emoji);
                                    @endphp
                                    @if ($count > 0)
                                        <form action="{{ route('reactions.store', ['topic' => $topic->id]) }}"
                                            method="post" onsubmit="event.preventDefault(); this.submit();">
                                            @csrf
                                            <input type="hidden" name="emoji" value="{{ $emoji }}">
                                            <button type="submit" name="emoji"
                                                style="{{ $hasReacted ? 'background-color: #ADE0EE;' : '' }}">{{ $emoji }}
                                                {{ $count }}</button>
                                        </form>
                                    @endif
                                @endforeach
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

    <script>
        function toggleEmojiPicker(button) {
            const picker = button.nextElementSibling;
            picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
        }

        function toggleMoreEmojis(button) {
            const moreEmojis = button.nextElementSibling;
            moreEmojis.style.display = moreEmojis.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</x-app-layout>
