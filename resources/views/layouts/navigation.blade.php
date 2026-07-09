<x-navbar x-data="{ open: false }" logoUrl="{{ route('dashboard') }}" maxWidth="max-w-7xl">
    <style>
        /* Light mode admin styles (default) */
        nav a {
            color: #4b5563 !important;
            border-bottom-color: transparent !important;
        }
        nav a:hover {
            color: #111827 !important;
            border-bottom-color: #e5e7eb !important;
        }
        nav a[class*="border-indigo-400"],
        nav a[class*="text-gray-900"] {
            color: #2b308b !important;
            border-bottom-color: #00aeef !important;
        }
        nav button {
            color: #4b5563 !important;
            background-color: transparent !important;
        }
        nav button:hover {
            color: #111827 !important;
        }
        nav button svg {
            stroke: #4b5563 !important;
        }

        /* Dark mode overrides (when .dark class is on html) */
        .dark nav {
            background-color: #1a1b4b !important;
            border-color: rgba(15, 16, 47, 0.3) !important;
        }
        .dark nav a {
            color: #d1d5db !important;
            border-bottom-color: transparent !important;
        }
        .dark nav a:hover {
            color: #ffffff !important;
            border-bottom-color: rgba(255, 255, 255, 0.2) !important;
        }
        .dark nav a[class*="border-indigo-400"],
        .dark nav a[class*="text-gray-900"] {
            color: #ffffff !important;
            border-bottom-color: #00aeef !important;
        }
        .dark nav button {
            color: #d1d5db !important;
            background-color: transparent !important;
        }
        .dark nav button:hover {
            color: #ffffff !important;
        }
        .dark nav button svg {
            stroke: #ffffff !important;
        }

        /* Mobile responsive drawer overlay overrides */
        .dark nav div.sm\:hidden {
            background-color: #15163f !important;
            border-top-color: #0f102f !important;
        }
        .dark nav div.sm\:hidden a {
            color: #d1d5db !important;
        }
        .dark nav div.sm\:hidden a:hover {
            background-color: #1e205c !important;
            color: #ffffff !important;
        }
        .dark nav div.sm\:hidden div {
            color: #ffffff !important;
        }
        .dark nav div.sm\:hidden div.text-gray-500 {
            color: #9ca3af !important;
        }
    </style>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-12">
            <div class="flex">
                <!-- Logo offset spacer -->
                <div class="w-40 h-12"></div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.questions.index')" :active="request()->routeIs('admin.questions.*')">
                        {{ __('Questions') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.tests.index')" :active="request()->routeIs('admin.tests.*')">
                        {{ __('Tests') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.codes.index')" :active="request()->routeIs('admin.codes.*')">
                        {{ __('Access Codes') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.sessions.index')" :active="request()->routeIs('admin.sessions.*')">
                        {{ __('Sessions') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown & Theme Toggle -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-3">
                <button type="button" class="logo-toggle-trigger p-2 text-slate-500 hover:text-slate-900 dark:text-neutral-400 dark:hover:text-white transition-colors duration-200 focus:outline-none" aria-label="Toggle Theme">
                    <!-- Sun Icon -->
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon Icon -->
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden rounded-[1.5rem] mt-1 overflow-hidden border border-slate-200 dark:border-zinc-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.questions.index')" :active="request()->routeIs('admin.questions.*')">
                {{ __('Questions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.tests.index')" :active="request()->routeIs('admin.tests.*')">
                {{ __('Tests') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.codes.index')" :active="request()->routeIs('admin.codes.*')">
                {{ __('Access Codes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.sessions.index')" :active="request()->routeIs('admin.sessions.*')">
                {{ __('Sessions') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center justify-between px-4">
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <button type="button" class="logo-toggle-trigger p-2 text-slate-500 hover:text-slate-900 dark:text-neutral-400 dark:hover:text-white transition-colors duration-200 focus:outline-none" aria-label="Toggle Theme">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</x-navbar>
