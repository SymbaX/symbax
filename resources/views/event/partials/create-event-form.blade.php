<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create event') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Create a new event.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('event.create') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Event Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', '')" required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="detail" :value="__('Details')" />
            <x-textarea id="detail" name="detail" type="text" class="mt-1 block w-full" required autocomplete="off">
                {{ old('detail', '') }}
            </x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('detail')" />
        </div>

        <div>
            <x-input-label for="category" :value="__('Category')" />
            <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('category')" />
        </div>

        <div>
            <x-input-label for="tag" :value="__('Tag')" />
            <x-text-input id="tag" name="tag" type="text" class="mt-1 block w-full" :value="old('tag', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('tag')" />
        </div>

        <div>
            <x-input-label for="conditions_of_participation" :value="__('Conditions of participation')" />
            <x-text-input id="conditions_of_participation" name="conditions_of_participation" type="text" class="mt-1 block w-full" :value="old('conditions_of_participation', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('conditions_of_participation')" />
        </div>

        <div>
            <x-input-label for="estarnal_link" :value="__('Extarnal links')" />
            <x-text-input id="estarnal_link" name="estarnal_link" type="text" class="mt-1 block w-full" :value="old('estarnal_link', '')" required autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('estarnal_link')" />
        </div>


        <div>
            <x-input-label for="datetime" :value="__('Datetime')" />
            <x-text-input id="datetime" name="datetime" type="text" class="mt-1 block w-full" :value="old('datetime', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('datetime')" />
        </div>


        <div>
            <x-input-label for="place" :value="__('Place')" />
            <x-text-input id="place" name="place" type="text" class="mt-1 block w-full" :value="old('place', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('place')" />
        </div>

        <div>
            <x-input-label for="number_of_people" :value="__('Number of people')" />
            <x-text-input id="number_of_people" name="number_of_people" type="text" class="mt-1 block w-full" :value="old('number_of_people', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('number_of_people')" />
        </div>

        <div>
            <x-input-label for="product_image" :value="__('product_image')" />
            <x-text-input id="product_image" name="product_image" type="file" class="mt-1 block w-full" :value="old('product_image', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('product_image')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'event-create')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

</section>