<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create event') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Create a new event.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="event_name" :value="__('Event Name')" />
            <x-text-input id="event_name" name="event_name" type="text" class="mt-1 block w-full" :value="old('event_name', 'test')" required autofocus autocomplete="event_name" />
            <x-input-error class="mt-2" :messages="$errors->get('event_name')" />
        </div>

        <div>
            <x-input-label for="event_details" :value="__('Details')" />
            <x-text-input id="event_details" name="event_details" type="text" class="mt-1 block w-full" :value="old('event_details', 'test')" required autofocus autocomplete="event_details" />
            <x-input-error class="mt-2" :messages="$errors->get('event_details')" />
        </div>

        <div>
            <x-input-label for="event_category" :value="__('Category')" />
            <x-text-input id="event_category" name="event_category" type="text" class="mt-1 block w-full" :value="old('event_category', 'test')" required autofocus autocomplete="event_category" />
            <x-input-error class="mt-2" :messages="$errors->get('event_category')" />
        </div>

        <div>
            <x-input-label for="event_tag" :value="__('Tag')" />
            <x-text-input id="event_tag" name="event_tag" type="text" class="mt-1 block w-full" :value="old('event_tag', 'test')" required autofocus autocomplete="event_tag" />
            <x-input-error class="mt-2" :messages="$errors->get('event_tag')" />
        </div>

        <div>
            <x-input-label for="event_conditions_of_participation" :value="__('Conditions of participation')" />
            <x-text-input id="event_conditions_of_participation" name="event_conditions_of_participation" type="text" class="mt-1 block w-full" :value="old('event_conditions_of_participation', 'test')" required autofocus autocomplete="event_conditions_of_participation" />
            <x-input-error class="mt-2" :messages="$errors->get('event_conditions_of_participation')" />
        </div>

        <div>
            <x-input-label for="event_extarnal_links" :value="__('Extarnal links')" />
            <x-text-input id="event_extarnal_links" name="event_extarnal_links" type="text" class="mt-1 block w-full" :value="old('event_extarnal_links', 'test')" required autofocus autocomplete="event_extarnal_links" />
            <x-input-error class="mt-2" :messages="$errors->get('event_extarnal_links')" />
        </div>

        <div>
            <x-input-label for="event_patetime" :value="__('Place')" />
            <x-text-input id="event_patetime" name="event_patetime" type="text" class="mt-1 block w-full" :value="old('event_patetime', 'test')" required autofocus autocomplete="event_patetime" />
            <x-input-error class="mt-2" :messages="$errors->get('event_patetime')" />
        </div>

        <div>
            <x-input-label for="event_number_of_people" :value="__('Number of people')" />
            <x-text-input id="event_number_of_people" name="event_number_of_people" type="text" class="mt-1 block w-full" :value="old('event_number_of_people', 'test')" required autofocus autocomplete="event_number_of_people" />
            <x-input-error class="mt-2" :messages="$errors->get('event_number_of_people')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

</section>
