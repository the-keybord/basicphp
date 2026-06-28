<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Guest Login Card Styles */
            .guest-card label {
                color: #d4d4d4 !important;
                font-weight: 600 !important;
            }
            .guest-card input[type="email"],
            .guest-card input[type="password"],
            .guest-card input[type="text"] {
                background-color: #18181b !important;
                border-color: #3f3f46 !important;
                color: #ffffff !important;
                border-radius: 12px !important;
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }
            .guest-card input[type="email"]:focus,
            .guest-card input[type="password"]:focus,
            .guest-card input[type="text"]:focus {
                border-color: #00aeef !important;
                box-shadow: 0 0 0 2px rgba(0, 174, 239, 0.25) !important;
            }
            .guest-card input[type="checkbox"] {
                background-color: #18181b !important;
                border-color: #3f3f46 !important;
            }
            .guest-card input[type="checkbox"]:checked {
                background-color: #2b308b !important;
                border-color: #2b308b !important;
            }
            .guest-card a {
                color: #a1a1aa !important;
                transition: color 0.15s ease;
            }
            .guest-card a:hover {
                color: #00aeef !important;
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
            }
            .guest-card button[type="submit"]:hover {
                opacity: 0.95 !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(0, 174, 239, 0.2) !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-tr from-[#0a0b22] via-[#010103] to-black">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-4">
                <a href="/">
                    <x-application-logo class="h-16 w-auto object-contain" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-[#121216]/95 border border-zinc-800 backdrop-blur-md shadow-2xl rounded-3xl guest-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
