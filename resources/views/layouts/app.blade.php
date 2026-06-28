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

        <style>
            /* Admin Layout & Card Theme Transitions */
            body {
                transition: background-color 0.2s ease, color 0.2s ease;
            }
            .bg-white, card, .shadow, input, select, textarea {
                transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            }

            /* Dark mode global overrides */
            .dark body {
                background-color: #0f1015 !important;
                color: #e2e8f0 !important;
            }
            .dark .bg-white {
                background-color: #161720 !important;
                color: #e2e8f0 !important;
                border-color: #232535 !important;
            }
            .dark header.bg-white {
                background-color: #161720 !important;
                border-bottom: 1px solid #232535 !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
            }
            
            /* Text elements accessibility in dark mode */
            .dark .text-gray-900,
            .dark .text-gray-800,
            .dark .text-gray-700 {
                color: #f1f5f9 !important;
            }
            .dark .text-gray-600,
            .dark .text-gray-500 {
                color: #94a3b8 !important;
            }

            /* Inputs & Selects in dark mode */
            .dark input,
            .dark textarea,
            .dark select,
            .dark .bg-gray-50 {
                background-color: #1e202e !important;
                border-color: #2e3146 !important;
                color: #ffffff !important;
            }
            .dark input:focus,
            .dark textarea:focus,
            .dark select:focus {
                border-color: #00aeef !important;
                box-shadow: 0 0 0 2px rgba(0, 174, 239, 0.25) !important;
            }

            /* Tables in dark mode */
            .dark table,
            .dark tr,
            .dark td,
            .dark th {
                border-color: #232535 !important;
            }
            .dark th {
                background-color: #191a24 !important;
                color: #94a3b8 !important;
            }
            .dark tbody tr:hover {
                background-color: #1e202e !important;
            }
        </style>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/zeceinfoblock.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#f8fafc] pt-28">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
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
