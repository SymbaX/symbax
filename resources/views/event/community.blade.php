@push('css')
    <link rel="stylesheet" href="{{ asset('css/event-community.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/loading.js') }}"></script>
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
                    <!-- ... フォームのコード ... -->

                    @forelse($topics as $topic)
                        <div class="border my-2 p-2 comment-box">
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
                                    <div class="p-2">{!! nl2br(e($topic->content)) !!}</div>
                                    <div class="text-secondary">{{ $topic->created_at }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>トピックがありません。</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
