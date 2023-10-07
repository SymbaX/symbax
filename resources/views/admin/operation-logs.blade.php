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

                                    <td class="text-left" data-label="{{ __('Detail') }}">
                                        <a href="#"
                                            onclick="openModal('details-{{ $operation_log->id }}'); return false;">View
                                            Details</a>

                                        <div id="details-{{ $operation_log->id }}" class="modal">
                                            <div class="modal-content">
                                                <span class="close-btn"
                                                    onclick="closeModal('details-{{ $operation_log->id }}');">&times;</span>
                                                <h2 class="font-bold text-lg">Log Details</h2>
                                                <p>{!! nl2br(e($operation_log->detail)) !!}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="{{ __('Date and time') }}">{{ $operation_log->created_at }}
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
<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        overflow-y: auto;
        /* Allows scrolling within the modal */
    }

    .modal-content {
        background-color: #f4f4f4;
        margin: 5% auto;
        padding: 20px;
        width: 70%;
        max-height: 80vh;
        /* This will make sure modal's height doesn't exceed 80% of the viewport height */
        overflow-y: auto;
        /* Allows content inside the modal to scroll */
        position: relative;
    }

    .close-btn {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        right: 20px;
        top: 0;
        cursor: pointer;
        z-index: 1;
        background: #f4f4f4;
        padding: 0 10px;
    }
</style>


<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>
