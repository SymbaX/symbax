<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="container mx-auto">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="max-w-xl mx-auto">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden">
                            <img id="preview"
                                src="{{ isset($user->profile_photo_path) ? Storage::url($user->profile_photo_path) : asset('img/default-user.png') }}"
                                alt="" class="w-16 h-16 object-cover">
                        </div>
                        <div class="ml-4"> <!-- この行のマージンを追加 -->
                            <h1 class="text-2xl font-semibold">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->login_id }}</p>
                            <p class="text-gray-600">{{ $user->college->name }}</p>
                            <p class="text-gray-600">{{ $user->department->name }}</p>
                        </div>
                    </div>
                    <div class="mt-6"> <!-- この行のマージンを調整 -->
                        <p class="text-lg leading-7">{{ $user->self_introduction }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

