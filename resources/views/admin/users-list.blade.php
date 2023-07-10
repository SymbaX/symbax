<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 border-b">ID</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Name</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Email</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Email Verified At</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Role</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">College ID</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Department ID</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">Created At</th>
                                <th class="px-6 py-3 bg-gray-100 border-b">編集</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 border-b">{{ $user->id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                                    <td class="px-6 py-4 border-b">
                                        @if ($user->email_verified_at != null)
                                            {{ $user->email_verified_at }}
                                        @else
                                            {{ __('Not verified') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 border-b">{{ $user->role }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->college_id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->department_id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->created_at }}</td>
                                    <td class="px-6 py-4 border-b">
                                        @if (auth()->user()->role == 'admin')
                                            <x-primary-button class="ml-4"
                                                onclick="openEditModal('{{ $user->id }}', '{{ $user->college_id }}', '{{ $user->department_id }}')">
                                                Edit
                                            </x-primary-button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit User</h3>
                        <div class="mt-2">
                            <div class="mt-4">
                                <x-input-label for="college" :value="__('College')" />
                                <select name="college" id="editUserCollege"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}">
                                            {{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <x-input-label for="department" :value="__('Department')" />
                                <select name="department" id="editUserDepartment"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            data-college-id="{{ $department->college_id }}"
                                            style="{{ $department->college_id == $user->college_id ? '' : 'display: none' }}">
                                            {{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-6">
                                <x-primary-button id="saveUserChangesButton"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                    Save Changes
                                </x-primary-button>
                            </div>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(userId, collegeId, departmentId) {
            const collegeSelect = document.getElementById('editUserCollege');
            const departmentSelect = document.getElementById('editUserDepartment');
            const saveButton = document.getElementById('saveUserChangesButton');

            // 選択されたユーザーのCollege IDを設定する
            for (let i = 0; i < collegeSelect.options.length; i++) {
                if (collegeSelect.options[i].value === collegeId) {
                    collegeSelect.options[i].selected = true;
                    break;
                }
            }

            // カレッジの選択肢が変更された時の処理
            collegeSelect.addEventListener('change', function() {
                changeDepartmentOptions();
            });

            // カレッジの初期選択に応じて学科の表示/非表示と選択状態を設定する
            changeDepartmentOptions();

            // Save buttonのクリックイベントを設定する
            saveButton.addEventListener('click', function() {
                const selectedCollegeId = collegeSelect.value;
                const selectedDepartmentId = departmentSelect.value;

                // TODO: バリデーションやデータの送信処理を実装する
                // サーバーに対してユーザーのCollege IDとDepartment IDを更新するリクエストを送信する等の処理が必要です
            });

            // モーダルウィンドウを表示する
            const modal = document.getElementById('editUserModal');
            modal.classList.remove('hidden');

            // カレッジに応じた学科の表示/非表示と選択状態を制御する関数
            function changeDepartmentOptions() {
                const selectedCollegeId = collegeSelect.value;

                for (let i = 0; i < departmentSelect.options.length; i++) {
                    const departmentOption = departmentSelect.options[i];
                    if (departmentOption.dataset.collegeId === selectedCollegeId) {
                        departmentOption.style.display = '';
                    } else {
                        departmentOption.style.display = 'none';
                    }
                }

                // 選択されたカレッジ内の学科を強制的に選択する
                const selectedDepartment = departmentSelect.querySelector(
                    `option[data-college-id="${selectedCollegeId}"][value="${departmentId}"]`
                );
                if (selectedDepartment) {
                    selectedDepartment.selected = true;
                } else {
                    // 学科が選択されていない場合は、最初のカレッジに関連する学科を選択する
                    const defaultDepartment = departmentSelect.querySelector(
                        `option[data-college-id="${selectedCollegeId}"]`
                    );
                    if (defaultDepartment) {
                        defaultDepartment.selected = true;
                    }
                }
            }
        }
    </script>
</x-app-layout>
