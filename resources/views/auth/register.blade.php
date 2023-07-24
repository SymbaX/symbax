<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- User id -->
        <div class="mt-4">
            <x-input-label for="user_id" :value="__('User Id')" />
            <x-text-input id="user_id" class="block mt-1 w-full" type="text" name="user_id" :value="old('user_id')" required
                autofocus />
            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-input-hint-text>
                {{ __('Only "@g.neec.ac.jp" domain can be registered.') }}
            </x-input-hint-text>
        </div>

        <!-- College -->
        <div class="mt-4">
            <x-input-label for="college" :value="__('College')" />
            <select name="college" id="college"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="" data-default="true">選択してください</option>
                @foreach ($colleges as $college)
                    <option value="{{ $college->id }}" {{ $selectedCollegeId == $college->id ? 'selected' : '' }}>
                        {{ $college->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('college')" class="mt-2" />
        </div>

        <!-- Department -->
        <div class="mt-4">
            <x-input-label for="department" :value="__('Department')" />
            <select name="department" id="department"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                {{ $selectedCollegeId ? '' : 'disabled' }}>
                <option value="" data-default="true">選択してください</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" data-college-id="{{ $department->college_id }}"
                        {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>

        <script>
            // Collegeの選択肢が変更されたときの処理
            $(document).ready(function() {
                // 初期表示時にDepartmentの選択肢を設定
                updateDepartmentOptions();

                $('#college').on('change', function() {
                    // 選択されたCollegeに一致するDepartmentを表示し、選択状態にする
                    updateDepartmentOptions();
                });

                // Departmentの選択肢を更新する関数
                function updateDepartmentOptions() {
                    var selectedCollegeId = $('#college').val();
                    var $departmentSelect = $('#department');
                    var $departmentOptions = $departmentSelect.find('option');

                    // Departmentの選択肢を無効化し、選択を解除する
                    $departmentOptions.prop('disabled', true).prop('selected', false);
                    $departmentOptions.hide().prop('selected', false);

                    // 選択してくださいのオプションを表示し、選択状態にする
                    var $defaultOption = $departmentSelect.find('option[data-default="true"]');
                    $defaultOption.prop('disabled', false).prop('selected', true);

                    if (selectedCollegeId !== '') {
                        // 選択されたCollegeに一致するDepartmentを表示し、選択状態にする
                        $departmentOptions.filter('[data-college-id="' + selectedCollegeId + '"]').prop('disabled',
                            false).css('visibility', 'visible');
                        $departmentOptions.filter('[data-college-id="' + selectedCollegeId + '"]').show();

                    }

                    // Departmentの選択肢を有効化する
                    $departmentSelect.prop('disabled', false);
                }
            });
        </script>



        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
