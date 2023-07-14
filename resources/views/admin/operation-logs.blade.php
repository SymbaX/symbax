@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operation logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="tb01">
                        <thead>
                            <tr class="head">
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('User id') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operation_logs as $operation_log)
                                <tr>
                                    <td data-label="{{ __('ID') }}">{{ $operation_log->id }}</td>
                                    <td data-label="{{ __('User id') }}">{{ $operation_log->user_name }}
                                        (ID:{{ $operation_log->user_id }})</td>
                                    <td data-label="{{ __('Action') }}">{{ $operation_log->action }}</td>
                                    <td data-label="{{ __('Date') }}">{{ $operation_log->created_at }}</>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $operation_logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
