<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('New event') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Create a new event.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

  
</section>
