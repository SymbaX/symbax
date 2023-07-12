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
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('ID') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('User id') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Action') }}</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operation_logs as $operation_log)
                                <tr>
                                    <td class="px-6 py-4 border-b">{{ $operation_log->id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $operation_log->user_name }}
                                        (ID:{{ $operation_log->user_id }})</td>
                                    <td class="px-6 py-4 border-b">{{ $operation_log->action }}</td>
                                    <td class="px-6 py-4 border-b">{{ $operation_log->created_at }}</td>
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
