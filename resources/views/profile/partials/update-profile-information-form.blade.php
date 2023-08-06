<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's name.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-picture-input />
            <x-input-error class="mt-2" :messages="$errors->get('picture')" />
        </div>

        <div>
            <x-input-label for="login_id" :value="__('Login Id')" />
            {{ $user->login_id }}
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="college" :value="__('College')" />
            {{ $college->name }}
        </div>

        <div>
            <x-input-label for="department" :value="__('Department')" />
            {{ $department->name }}
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            {{ $user->email }}
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
