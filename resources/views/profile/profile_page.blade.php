<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="mr-3">
                        <img id="preview"
                            src="{{ isset($user->profile_photo_path) ? Storage::url($user->profile_photo_path) : asset('img/default-user.png') }}"
                            alt="" class="w-16 h-16 rounded-full object-cover border-none bg-gray-200">
                    </div>
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->login_id }}</p>
                    <p>{{ $user->college->name }}</p>
                    <p>{{ $user->department->name }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>