<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('index.home') }}">
                        <img src="{{ asset('img/logo.svg') }}" width="50" height="50">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('index.home')" :active="request()->routeIs('index.home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('index.all')" :active="request()->routeIs('index.all')">
                        {{ __('Event list all') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- search -->
                @if (
                    !request()->routeIs('index.search') &&
                        !request()->routeIs('event.create') &&
                        !request()->routeIs('event.edit') &&
                        !request()->routeIs('profile.edit'))
                    <form action="{{ route('index.search') }}" method="GET">
                        <div class="flex rounded-md shadow-sm">
                            <button type="submit" onclick="this.form.target='_blank'"
                                class="inline-flex flex-shrink-0 justify-center items-center h-[2.875rem] w-[2.875rem] rounded-l-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all text-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </button>
                            <input type="text" name="keyword" value="" placeholder={{ __('Keyword Search') }}
                                id="hs-leading-button-add-on-with-icon" name="hs-leading-button-add-on-with-icon"
                                class="py-3 px-4 block w-full border-gray-200 shadow-sm rounded-r-md text-sm focus:z-10 focus:border-gray-500 focus:ring-gray-500">
                            <input type="hidden" name="selectcategory">
                        </div>
                    </form>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('index.join')">
                            {{ __('Join event') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('index.organizer')">
                            {{ __('Organizer event') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (auth()->user()->role_id === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">
                                {{ __('Admin dashboard') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link href="https://forms.gle/1Vvjkeda4tHZT9d3A">
                            {{ __('Inquiry form') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                onclick="event.preventDefault();
                                    this.closest('form').submit();"
                                style="cursor: pointer;">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('index.home')" :active="request()->routeIs('index.home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('index.all')" :active="request()->routeIs('index.all')">
                {{ __('Event list all') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('index.search')">
                    {{ __('検索') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('index.join')">
                    {{ __('Join event') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('index.organizer')">
                    {{ __('Organizer event') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (auth()->user()->role_id === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">
                        {{ __('Admin dashboard') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link href="https://forms.gle/1Vvjkeda4tHZT9d3A">
                    {{ __('Inquiry form') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link onclick="event.preventDefault();
            this.closest('form').submit();"
                        style="cursor: pointer;">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                </form>
            </div>
        </div>
    </div>
</nav>
