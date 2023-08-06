<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit event') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Edit event') }}
        </p>
    </header>

    <form method="post" action="{{ route('event.update', ['event_id' => $event->id]) }}" class="mt-6 space-y-6"
        enctype="multipart/form-data">
        @csrf
        @method('patch')

        <input type="hidden" name="edit_token" value="{{ session('edit_token') }}">
        <div>
            <x-input-label for="name" :value="__('Event Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $event->name)"
                required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="detail" :value="__('Detail')" />
            <x-textarea id="detail" name="detail" type="text" class="mt-1 block w-full" required
                autocomplete="off">{{ old('detail', $event->detail) }}</x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('detail')" />
        </div>

        <div>
            <x-input-label for="category" :value="__('Category')" />

            <select name="category" id="category"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category', $event->category) === $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="tag" :value="__('Tag')" />
            <x-text-input id="tag" name="tag" type="text" class="mt-1 block w-full" :value="old('tag', $event->tag)"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('tag')" />
        </div>

        <div>
            <x-input-label for="participation_condition" :value="__('Participation condition')" />
            <x-text-input id="participation_condition" name="participation_condition" type="text"
                class="mt-1 block w-full" :value="old('participation_condition', $event->participation_condition)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('participation_condition')" />
        </div>

        <div>
            <x-input-label for="external_link" :value="__('External link')" />
            <x-text-input id="external_link" name="external_link" type="text" class="mt-1 block w-full"
                :value="old('external_link', $event->external_link)" required autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('external_link')" />
        </div>

        <div>
            <x-input-label for="date" :value="__('Date')" />
            {{ $event->date }}
        </div>

        <div>
            <x-input-label for="deadline_date" :value="__('Deadline date')" />
            {{ $event->deadline_date }}
        </div>

        <div>
            <x-input-label for="place" :value="__('Location')" />
            <x-text-input id="place" name="place" type="text" class="mt-1 block w-full" :value="old('place', $event->place)"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('place')" />
        </div>

        <div>
            <x-input-label for="number_of_recruits" :value="__('Number of recruits')" />
            <x-text-input id="number_of_recruits" name="number_of_recruits" type="number" class="mt-1 block w-full"
                :value="old('number_of_recruits', $event->number_of_recruits)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('number_of_recruits')" />
        </div>

        <div>
            <x-input-label for="image_path" :value="__('Image')" />
            <input id="image_path" name="image_path" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Update') }}</x-primary-button>

            @if (session('status') === 'event-create')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

</section>
