<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <a href="{{ route('admin.operation-logs') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('Operation logs') }}
                    </a>
                    <br /><br />
                    <a href="{{ route('admin.users') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('Account lists') }}</a>
                    <br />
                    <a href="{{ route('admin.events') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('Event lists') }}</a>
                    <br /><br />
                    <a href="{{ route('admin.mail') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('Mail send') }}</a>
                    <br /><br />
                    <a href="{{ route('admin.title-image') }}" class="text-blue-500 hover:text-blue-700 link_confirm">
                        {{ __('Generate title images in batches') }}</a>
                    <br />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    jQuery(function($) {
        const linkConfirms = $('.link_confirm');
        if (linkConfirms.length) {
            linkConfirms.click(function(event) {
                const resultConfirm = confirm('この操作はOGPをあとから生成する機能です。');
                if (!resultConfirm) {
                    event.preventDefault();
                }
            })
        }
    });
</script>
