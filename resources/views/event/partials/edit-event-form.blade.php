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
            <x-input-label for="name" class="required" :value="__('Event Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $event->name)"
                required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="detail" class="required" :value="__('Detail')" />
            <x-textarea id="detail" name="detail" type="text" class="mt-1 block w-full" required
                autocomplete="off">{{ old('detail', $event->detail) }}</x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('detail')" />
        </div>

        <div>
            <x-input-label for="category" class="required" :value="__('Category')" />

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
            <x-input-label for="tag" class="required" :value="__('Tag')" />
            <x-text-input id="tag" name="tag" type="text" class="mt-1 block w-full" :value="old('tag', $event->tag)"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('tag')" />
        </div>

        <div>
            <x-input-label for="participation_condition" class="required" :value="__('Participation condition')" />
            <x-text-input id="participation_condition" name="participation_condition" type="text"
                class="mt-1 block w-full" :value="old('participation_condition', $event->participation_condition)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('participation_condition')" />
        </div>

        <div>
            <x-input-label for="external_link" class="required" :value="__('External link')" />
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
            <x-input-label for="place" class="required" :value="__('Location')" />
            <x-text-input id="place" name="place" type="text" class="mt-1 block w-full" :value="old('place', $event->place)"
                required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('place')" />
        </div>

        <div>
            <x-input-label for="number_of_recruits" class="required" :value="__('Number of recruits')" />
            <x-text-input id="number_of_recruits" name="number_of_recruits" type="number" class="mt-1 block w-full"
                :value="old('number_of_recruits', $event->number_of_recruits)" required autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('number_of_recruits')" />
        </div>

        <div>
        <img class="event_image" src="{{ Storage::url($event->image_path_a) }} " alt="">
            <x-input-label for="image_path_a" class="optional" :value="__('Image_1')" />
            <input id="image_path_a" name="image_path_a" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path_a')" />
        </div>

        <div>
            <img class="event_image" src="{{ Storage::url($event->image_path_b) }} " alt="">
            <x-input-label for="image_path_b" class="optional" :value="__('Image_2')" />
            <input id="image_path_b" name="image_path_b" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path_b')" />
            <input type="checkbox" id="img_delete_b" name="img_delete_b" />
            <label for="img_delete_b">{{ __('Delete Image') }}</label>
        </div>

        <div>
            <img class="event_image" src="{{ Storage::url($event->image_path_c) }} " alt="">
            <x-input-label for="image_path_c" class="optional" :value="__('Image_3')" />
            <input id="image_path_c" name="image_path_c" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path_c')" />
            <input type="checkbox" id="img_delete_c" name="img_delete_c" />
            <label for="img_delete_c">{{ __('Delete Image') }}</label>
        </div>

        <div>
            <img class="event_image" src="{{ Storage::url($event->image_path_d) }} " alt="">
            <x-input-label for="image_path_d" class="optional" :value="__('Image_4')" />
            <input id="image_path_d" name="image_path_d" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path_d')" />
            <input type="checkbox" id="img_delete_d" name="img_delete_d" />
            <label for="img_delete_d">{{ __('Delete Image') }}</label>
        </div>

        <div>
            <img class="event_image" src="{{ Storage::url($event->image_path_e) }} " alt="">
            <x-input-label for="image_path_e" class="optional" :value="__('Image_5')" />
            <input id="image_path_e" name="image_path_e" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image_path_e')" />
            <input type="checkbox" id="img_delete_e" name="img_delete_e" />
            <label for="img_delete_e">{{ __('Delete Image') }}</label>
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
