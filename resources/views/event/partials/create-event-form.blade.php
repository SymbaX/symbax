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
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', '')"
                required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="detail" :value="__('Detail')" />
            <x-textarea id="detail" name="detail" type="text" class="mt-1 block w-full" required
                autocomplete="off">
                {{ old('detail', '') }}
            </x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('detail')" />
        </div>

        <div>
            <x-input-label for="category" :value="__('Category')" />
            <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category', '')"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('category')" />
        </div>

        <div>
            <x-input-label for="tag" :value="__('Tag')" />
            <x-text-input id="tag" name="tag" type="text" class="mt-1 block w-full" :value="old('tag', '')"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('tag')" />
        </div>

        <div>
            <x-input-label for="participation_condition" :value="__('Participation condition')" />
            <x-text-input id="participation_condition" name="participation_condition" type="text"
                class="mt-1 block w-full" :value="old('participation_condition', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('participation_condition')" />
        </div>

        <div>
            <x-input-label for="external_link" :value="__('External link')" />
            <x-text-input id="external_link" name="external_link" type="text" class="mt-1 block w-full"
                :value="old('external_link', '')" required autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('external_link')" />
        </div>

        <div>
            <x-input-label for="date" :value="__('Date')" />
            <x-text-input id="date" name="date" type="text" class="mt-1 block w-full" :value="old('date', '')"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('date')" />
        </div>

        <div>
            <x-input-label for="deadline_date" :value="__('Deadline date')" />
            <x-text-input id="deadline_date" name="deadline_date" type="text" class="mt-1 block w-full"
                :value="old('deadline_date', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('deadline_date')" />
        </div>

        <div>
            <x-input-label for="place" :value="__('Place')" />
            <x-text-input id="place" name="place" type="text" class="mt-1 block w-full" :value="old('place', '')"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('place')" />
        </div>

        <div>
            <x-input-label for="number_of_recruits" :value="__('Number of recruits')" />
            <x-text-input id="number_of_recruits" name="number_of_recruits" type="text" class="mt-1 block w-full"
                :value="old('number_of_recruits', '')" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('number_of_recruits')" />
        </div>

        <div>
            <x-input-label for="image_path" :value="__('Image')" />
            <x-text-input id="image_path" name="image_path" type="file" class="mt-1 block w-full" :value="old('image_path', '')"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('image_path')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'event-create')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

</section>
