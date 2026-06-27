<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ZeceInfo') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased h-screen flex flex-col">

    <header class="w-full p-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-blue-600">ZeceInfo</div>
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900">Admin Login</a>
    </header>

    <main class="flex-grow flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 space-y-6 text-center border border-gray-100">
            
            <h1 class="text-3xl font-extrabold text-gray-900">Join a Session</h1>
            <p class="text-gray-500">Enter the 6-character code provided by your instructor to begin.</p>

            <form action="{{ route('access.code') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <input 
                        type="text" 
                        name="access_code" 
                        maxlength="6"
                        placeholder="e.g. DEMO12"
                        class="w-full text-center text-3xl tracking-widest font-bold uppercase border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-4"
                        required
                        autocomplete="off"
                    >
                    @error('access_code')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-lg transition-colors duration-200 text-lg shadow-md">
                    Enter
                </button>
            </form>

        </div>
    </main>

</body>
</html>