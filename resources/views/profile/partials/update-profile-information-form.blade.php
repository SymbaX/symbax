@push('css')
    <link rel="stylesheet" href="{{ asset('css/update-profile-information-form.blade.css') }}">
@endpush
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

        <div class="form-group">
            <label for="self_introduction">自己紹介文:</label>
            <x-textarea name="self_introduction" id="self_introduction"  required autocomplete="self_introduction" rows="4"> {{old('self_introduction','')}} 
            </x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('self_introduction')"/>
                
        </div>
        <script>
            // 自己紹介文のテキストエリアを取得
            var selfIntroductionTextarea = document.getElementById('self_introduction');
        
            // テキストエリアの高さを設定する関数
            function resizeTextarea() {
                selfIntroductionTextarea.style.height = 'auto';
                selfIntroductionTextarea.style.height = (selfIntroductionTextarea.scrollHeight) + 'px';
            }
        
            // ページ読み込み時に一度高さを調整する
            window.addEventListener('load', resizeTextarea);
        
            // テキストが入力されたら高さを調整する
            selfIntroductionTextarea.addEventListener('input', resizeTextarea);
        </script>

        <div>
            <x-input-label for="login_id" :value="__('Login Id')" />
            {{ $user->login_id }}
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name"/>
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