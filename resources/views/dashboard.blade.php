<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Admin Control Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl shadow-xl text-white p-8 md:p-10 border border-blue-500/10">
                <h3 class="text-2xl md:text-3xl font-black mb-2">Welcome Back, {{ Auth::user()->name }}!</h3>
                <p class="text-blue-100 text-sm md:text-base max-w-2xl leading-relaxed">
                    Manage your database questions, create exams, issue student entry codes, and inspect active testing sessions from this central dashboard.
                </p>
            </div>

            <!-- Management Tools Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Question Manager -->
                <a href="{{ route('admin.questions.index') }}" class="group block bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-blue-300 dark:hover:border-blue-800 hover:shadow-md transition duration-250">
                    <div class="p-6.5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-450 rounded-xl group-hover:scale-110 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xxs font-black text-blue-500 uppercase tracking-widest bg-blue-50 dark:bg-blue-950/40 px-2.5 py-1 rounded-md">Live</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Question Bank</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-450 leading-relaxed">Import and edit XML questions, map media assets, and select interactive correct answers.</p>
                    </div>
                </a>

                <!-- Categories & Subcategories -->
                <a href="{{ route('admin.categories.index') }}" class="group block bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-indigo-300 dark:hover:border-indigo-800 hover:shadow-md transition duration-250">
                    <div class="p-6.5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-450 rounded-xl group-hover:scale-110 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <span class="text-xxs font-black text-indigo-500 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 rounded-md">Live</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Categories Manager</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-450 leading-relaxed">Structure test subjects. Configure default question sizing and timers for each category.</p>
                    </div>
                </a>

                <!-- Test Manager -->
                <a href="{{ route('admin.tests.index') }}" class="group block bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-purple-300 dark:hover:border-purple-800 hover:shadow-md transition duration-250">
                    <div class="p-6.5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-450 rounded-xl group-hover:scale-110 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <span class="text-xxs font-black text-purple-500 uppercase tracking-widest bg-purple-50 dark:bg-purple-950/40 px-2.5 py-1 rounded-md">Live</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Test Manager</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-450 leading-relaxed">Group questions into exams, modify test metadata, and preview full student exam mockups.</p>
                    </div>
                </a>

                <!-- Access Codes -->
                <a href="{{ route('admin.codes.index') }}" class="group block bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-amber-300 dark:hover:border-amber-800 hover:shadow-md transition duration-250">
                    <div class="p-6.5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-450 rounded-xl group-hover:scale-110 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <span class="text-xxs font-black text-amber-500 uppercase tracking-widest bg-amber-50 dark:bg-amber-950/40 px-2.5 py-1 rounded-md">Live</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Access Codes</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-450 leading-relaxed">Generate entry codes for exams, configure modifiers (shuffle, post-submit settings), and share links.</p>
                    </div>
                </a>

                <!-- Sessions -->
                <a href="{{ route('admin.sessions.index') }}" class="group block bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-teal-300 dark:hover:border-teal-800 hover:shadow-md transition duration-250">
                    <div class="p-6.5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-450 rounded-xl group-hover:scale-110 transition duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xxs font-black text-teal-500 uppercase tracking-widest bg-teal-50 dark:bg-teal-950/40 px-2.5 py-1 rounded-md">Live</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Test Sessions</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-450 leading-relaxed">Monitor active sessions in real-time, interrupt test taking, and inspect graded session sheets.</p>
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>