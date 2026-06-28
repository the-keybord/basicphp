<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Session - {{ $test->name }}</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <link rel="icon" type="image/png" href="{{ asset('images/zeceinfoblock.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .bg-white {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .dark body {
            background-color: #0f1015 !important;
            color: #f1f5f9 !important;
        }
        .dark .bg-white {
            background-color: #161720 !important;
            border-color: #232535 !important;
            color: #f1f5f9 !important;
        }
        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-gray-700 {
            color: #f1f5f9 !important;
        }
        .dark .text-gray-650,
        .dark .text-gray-600,
        .dark .text-gray-500 {
            color: #94a3b8 !important;
        }
        .dark input {
            background-color: #1e202e !important;
            border-color: #2e3146 !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased min-h-screen flex flex-col justify-between">

    <header class="w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-2 logo-toggle-trigger cursor-pointer">
            <img src="{{ asset('images/zeceinfoblock.png') }}" alt="ZeceInfo Logo" class="h-8 w-auto object-contain">
        </div>
        <div class="flex items-center space-x-4">
            <button type="button" class="logo-toggle-trigger p-2 text-slate-500 hover:text-slate-900 dark:text-neutral-400 dark:hover:text-white transition-colors duration-200 focus:outline-none" aria-label="Toggle Theme">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">Cancel</a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Test Title Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                <span class="px-2.5 py-1 bg-white/20 text-white border border-white/10 rounded-full text-xxs font-bold uppercase tracking-widest block w-max mb-3">
                    Exam Session Ready
                </span>
                <h1 class="text-2xl font-black leading-tight">{{ $test->name }}</h1>
                
                <div class="flex items-center space-x-6 mt-4 text-sm text-blue-100">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $test->duration_minutes }} min limit</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ count($test->question_ids) }} questions</span>
                    </div>
                </div>
            </div>

            <!-- Student Join Form -->
            <div class="p-8">
                <form action="{{ route('test.start', $codeModel->code) }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">First Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                name="firstname" 
                                value="{{ old('firstname') }}" 
                                placeholder="e.g. John" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" 
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Last Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                name="lastname" 
                                value="{{ old('lastname') }}" 
                                placeholder="e.g. Doe" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" 
                                required
                            >
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-xl transition duration-150 text-base shadow-md hover:shadow-lg">
                            Begin Test Session
                        </button>
                        <p class="text-xxs text-gray-400 text-center mt-3 leading-relaxed">
                            By clicking Begin, your timer will start immediately. Please do not close or refresh this browser tab during the examination.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="w-full text-center py-6 text-xs text-gray-400">
        &copy; {{ date('Y') }} ZeceInfo. All rights reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Secret keyboard shortcut toggle (D or d)
            document.addEventListener('keydown', (e) => {
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
                    return;
                }
                if (e.key === 'd' || e.key === 'D') {
                    toggleTheme();
                }
            });

            // Logo click toggler
            document.querySelectorAll('.logo-toggle-trigger').forEach(el => {
                el.addEventListener('click', (e) => {
                    toggleTheme();
                });
            });

            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                window.dispatchEvent(new Event('theme-changed'));
            }
        });
    </script>
</body>
</html>
