@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/user-edit-modal.js') }}" defer></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">

                    <x-input-error class="mt-2" :messages="$errors->get('login_id')" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <x-input-error :messages="$errors->get('college')" class="mt-2" />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />

                    <table class="tb01">
                        <tr class="head">
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Login Id') }}</th>
                            <th>{{ __('Icon') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('College') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Edit') }}</th>
                        </tr>
                        @foreach ($users as $user)
                            <tr>
                                <td data-label="{{ __('ID') }}">{{ $user->id }}</td>
                                <td data-label="{{ __('Login Id') }}">{{ $user->login_id }}</td>
                                <td data-label="{{ __('Icon') }}">
                                    <div class="mr-3">
                                        <img src="{{ isset($user->profile_photo_path) ? Storage::url($user->profile_photo_path) : asset('img/default-user.png') }}"
                                            alt=""
                                            class="w-16 h-16 rounded-full object-cover border-none bg-gray-200">
                                    </div>
                                </td>
                                <td data-label="{{ __('Name') }}">{{ $user->name }}</td>
                                <td data-label="{{ __('Email') }}">
                                    @if ($user->email_verified_at != null)
                                        <span class="text-green-500">◯</span>
                                    @else
                                        <span class="text-red-500">✕</span>
                                    @endif

                                    {{ $user->email }}
                                </td>
                                <td data-label="{{ __('Role') }}">{{ $user->role->name }}</td>
                                <td data-label="{{ __('College') }}">{{ $user->college->name }}</td>
                                <td data-label="{{ __('Department') }}">{{ $user->department->name }}</td>
                                <td data-label="{{ __('Created At') }}">{{ $user->created_at }}</td>
                                <td data-label="{{ __('Edit') }}">
                                    <x-primary-button
                                        onclick="openEditModal('{{ $user->id }}','{{ $user->login_id }}','{{ $user->name }}','{{ $user->email }}','{{ $user->college_id }}', '{{ $user->department_id }}', '{{ $user->role_id }}')">
                                        {{ __('Edit') }}</x-primary-button>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                    {{ $users->links('vendor.pagination.tailwind02') }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.user-edit-modal')
</x-app-layout>
