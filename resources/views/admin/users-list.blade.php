@push('script')
    <script src="{{ asset('script/user-edit-modal.js') }}" defer></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('ID') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Name') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Email') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Email Verified At') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Role') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('College') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Department') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Created At') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Edit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 border-b">{{ $user->id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                                    <td class="px-6 py-4 border-b">
                                        @if ($user->email_verified_at != null)
                                            {{ $user->email_verified_at }}
                                        @else
                                            {{ __('Not verified') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 border-b">{{ $user->role->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->college->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->department->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->created_at }}</td>
                                    <td class="px-6 py-4 border-b">
                                        <x-primary-button class="ml-4"
                                            onclick="openEditModal('{{ $user->id }}', '{{ $user->college_id }}', '{{ $user->department_id }}', '{{ $user->role_id }}')">
                                            {{ __('Edit') }}</x-primary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.user-edit-modal')
</x-app-layout>
