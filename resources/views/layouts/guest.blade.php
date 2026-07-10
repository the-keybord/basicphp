<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <title>ZeceInfo</title>

        <!-- Favicon -->
        <link class="favicon" rel="icon" type="image/png" href="{{ asset('images/zeceinfoblock.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Guest Login Card Transitions & Themes */
            body {
                transition: background-color 0.2s ease, color 0.2s ease;
            }
            .guest-card {
                transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            }

            /* Light mode layout styling (default) */
            .guest-card label {
                color: #4b5563 !important;
                font-weight: 600 !important;
            }
            .guest-card input[type="email"],
            .guest-card input[type="password"],
            .guest-card input[type="text"] {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
                color: #0f172a !important;
                border-radius: 12px !important;
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }
            .guest-card input[type="email"]:focus,
            .guest-card input[type="password"]:focus,
            .guest-card input[type="text"]:focus {
                border-color: #2b308b !important;
                box-shadow: 0 0 0 2px rgba(43, 48, 139, 0.2) !important;
            }
            .guest-card input[type="checkbox"] {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
            }
            .guest-card input[type="checkbox"]:checked {
                background-color: #2b308b !important;
                border-color: #2b308b !important;
            }
            .guest-card a {
                color: #64748b !important;
                transition: color 0.15s ease;
            }
            .guest-card a:hover {
                color: #2b308b !important;
            }
            .guest-card button[type="submit"] {
                background: linear-gradient(135deg, #2b308b 0%, #00aeef 100%) !important;
                border: none !important;
                border-radius: 12px !important;
                padding-top: 10px !important;
                padding-bottom: 10px !important;
                font-weight: 700 !important;
                letter-spacing: 0.05em !important;
                transition: all 0.2s ease-in-out !important;
                color: #ffffff !important;
            }
            .guest-card button[type="submit"]:hover {
                opacity: 0.95 !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(0, 174, 239, 0.2) !important;
            }

            /* Dark mode layout styling (when html.dark is present) */
            .dark .guest-card label {
                color: #d4d4d4 !important;
                font-weight: 600 !important;
            }
            .dark .guest-card input[type="email"],
            .dark .guest-card input[type="password"],
            .dark .guest-card input[type="text"] {
                background-color: #18181b !important;
                border-color: #3f3f46 !important;
                color: #ffffff !important;
            }
            .dark .guest-card input[type="email"]:focus,
            .dark .guest-card input[type="password"]:focus,
            .dark .guest-card input[type="text"]:focus {
                border-color: #00aeef !important;
                box-shadow: 0 0 0 2px rgba(0, 174, 239, 0.25) !important;
            }
            .dark .guest-card input[type="checkbox"] {
                background-color: #18181b !important;
                border-color: #3f3f46 !important;
            }
            .dark .guest-card input[type="checkbox"]:checked {
                background-color: #2b308b !important;
                border-color: #2b308b !important;
            }
            .dark .guest-card a {
                color: #a1a1aa !important;
            }
            .dark .guest-card a:hover {
                color: #00aeef !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50 dark:bg-gradient-to-tr dark:from-[#0a0b22] dark:via-[#010103] dark:to-black min-h-screen transition-colors duration-300">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-4">
                <a href="/" class="logo-toggle-trigger">
                    <x-application-logo class="h-16 w-auto object-contain" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white dark:bg-[#121216]/95 border border-slate-200 dark:border-zinc-800 backdrop-blur-md shadow-xl dark:shadow-2xl rounded-3xl guest-card relative">
                <!-- Top Right Theme Toggle -->
                <div class="absolute top-4 right-4">
                    <button type="button" class="logo-toggle-trigger p-2 text-slate-500 hover:text-slate-900 dark:text-neutral-400 dark:hover:text-white transition-colors duration-200 focus:outline-none" aria-label="Toggle Theme">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>
                {{ $slot }}
            </div>
        </div>

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
