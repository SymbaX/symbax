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
                                <th>{{ __('Log id') }}</th>
                                <th>{{ __('User id') }}</th>
                                <th>{{ __('Target event id') }}</th>
                                <th>{{ __('Target user id') }}</th>
                                <th>{{ __('Target topic id') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Detail') }}</th>
                                <th>{{ __('Date and time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operation_logs as $operation_log)
                                <tr>
                                    <td data-label="{{ __('Log id') }}">{{ $operation_log->id }}</td>
                                    <td data-label="{{ __('User id') }}">{{ $operation_log->user_name }}
                                        (ID:{{ $operation_log->user_id }})
                                    </td>
                                    <td data-label="{{ __('Target event id') }}">{{ $operation_log->target_event_id }}
                                    </td>
                                    <td data-label="{{ __('Target user id') }}">{{ $operation_log->target_user_id }}
                                    </td>
                                    <td data-label="{{ __('Target topiic id') }}">
                                        {{ $operation_log->target_topic_id }}</td>
                                    <td data-label="{{ __('Action') }}">{{ $operation_log->action }}</td>
                                    <td class="text-left" data-label="{{ __('Detail') }}">{!! nl2br(e($operation_log->detail)) !!}</td>
                                    <td data-label="{{ __('Date and time') }}">{{ $operation_log->created_at }}</>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $operation_logs->links('vendor.pagination.tailwind02') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
