<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Session - {{ $test->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased min-h-screen flex flex-col justify-between">

    <header class="w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
        <div class="text-2xl font-bold text-blue-600">ZeceInfo</div>
        <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Cancel</a>
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

</body>
</html>
