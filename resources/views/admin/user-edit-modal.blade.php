<div id="editUserModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div
            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeEditModal()"
                            class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Edit') }}</h3>
                    <div class="mt-4">
                        <form method="post" action="{{ route('admin.user.update', 'USER_ID') }}" class="mt-6 space-y-6"
                            id="editUserForm">
                            @csrf
                            @method('patch')
                            <div class="mt-2">
                                <x-input-label for="college" :value="__('College')" />
                                <select name="college" id="editUserCollege"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
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
                            <div class="mt-4">
                                <x-input-label for="role" :value="__('Role')" />
                                <select name="role" id="editUserRole"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ intval($role->id) === $user->role ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>


                            </div>
                            <div class="mt-6">
                                <x-primary-button id="saveUserChangesButton"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                    {{ __('Update') }}
                                </x-primary-button>
                            </div>


                            <br />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
