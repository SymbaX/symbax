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
                        <input class="form-control my-2" type="text" name="name" placeholder="ここに名前を入力">
                        <textarea class="form-control my-2" name="content" rows="4" placeholder="ここにコメントを入力"></textarea>
                        <input class="form-control my-2" type="submit" value="送信">
                    </form>
                    
                    @forelse($topics as $topic)
                    <div class="border my-2 p-2">
                        <div class="text-secondary">{{ $topic->name }} さん</div>
                        <div class="p-2">{!! nl2br(e($topic->content)) !!}</div>
                        <div class="text-secondary">投稿日:{{ $topic->created_at }}</div>
                    </div>
                    @empty
                    <p>トピックがありません。</p>
                    @endforelse
                    

                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
