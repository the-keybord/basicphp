<x-app-layout title="Access Codes">
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Alert Banner -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
                @if(session('new_code'))
                    <button 
                        onclick="showFullscreenCode('{{ session('new_code') }}', '{{ session('new_code_test') }}')"
                        class="ml-4 px-3.5 py-1.5 bg-green-600 hover:bg-green-705 text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3M8 21h8M12 17v4M5 4h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                        Show on Whiteboard
                    </button>
                @endif
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 items-start" x-data="{ showExpired: false }">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Title & Generate Code Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-lg text-gray-800 leading-tight border-b border-gray-100 pb-3">
                        Access Codes
                    </h2>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.codes.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Generate Access Code
                        </a>
                    </div>
                </div>

                <!-- Status Metadata Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Status Overview</span>
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-gray-100/50">
                        <span class="text-gray-500">Total Codes:</span>
                        <span class="font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ count($codes) }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <span class="text-gray-500">Show Expired:</span>
                        <button 
                            @click="showExpired = !showExpired" 
                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="showExpired ? 'bg-blue-600' : 'bg-gray-200'"
                        >
                            <span 
                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="showExpired ? 'translate-x-4' : 'translate-x-0'"
                            ></span>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Table area) -->
            <main class="flex-grow w-full min-w-0 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900">
                @if($codes->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No access codes found</h3>
                        <p class="mt-1 text-sm text-gray-500">Create an access code pointing to a test for students to join test sessions.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.codes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">
                                Generate First Code
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xxs font-bold text-gray-500 uppercase tracking-wider">Access Code</th>
                                    <th class="px-4 py-2.5 text-left text-xxs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-2.5 text-left text-xxs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2.5 text-left text-xxs font-bold text-gray-500 uppercase tracking-wider">Expires At</th>
                                    <th class="px-4 py-2.5 text-right text-xxs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($codes as $code)
                                    @php
                                        $isValid = $code->isValid();
                                    @endphp
                                    <tr 
                                        x-show="showExpired || {{ $isValid ? 'true' : 'false' }}"
                                        class="hover:bg-gray-50/50 transition"
                                    >
                                        <td class="px-4 py-2.5 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-mono font-black text-sm tracking-wider text-blue-600 bg-blue-50/50 px-2 py-0.5 rounded border border-blue-100 select-all">
                                                    {{ $code->code }}
                                                </span>
                                                <button 
                                                    onclick="navigator.clipboard.writeText('{{ url('code=' . $code->code) }}'); const el = this.nextElementSibling; el.classList.remove('opacity-0'); setTimeout(() => el.classList.add('opacity-0'), 2000)"
                                                    class="inline-flex items-center p-1 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-100 transition" 
                                                    title="Copy Direct Link"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                                    </svg>
                                                </button>
                                                <span class="absolute left-full ml-1 bg-gray-800 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 transition-opacity duration-300 pointer-events-none whitespace-nowrap z-50">
                                                    Copied!
                                                </span>
                                                <button 
                                                    onclick="showFullscreenCode('{{ $code->code }}', '{{ $code->test ? addslashes($code->test->name) : 'External Resource' }}')"
                                                    class="inline-flex items-center p-1 text-gray-500 hover:text-indigo-650 hover:bg-indigo-50 rounded-lg border border-transparent hover:border-indigo-100 transition" 
                                                    title="Show Code on Whiteboard"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3M8 21h8M12 17v4M5 4h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                                    </svg>
                                                </button>
                                                <a 
                                                    href="{{ route('admin.sessions.index', ['code' => $code->code]) }}"
                                                    class="inline-flex items-center p-1 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg border border-transparent hover:border-purple-100 transition" 
                                                    title="Session Review"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/>
                                                    </svg>
                                                </a>
                                                <a 
                                                    href="{{ route('admin.codes.analytics', $code) }}"
                                                    class="inline-flex items-center p-1 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg border border-transparent hover:border-emerald-100 transition" 
                                                    title="Performance Matrix & Analytics"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                                    </svg>
                                                </a>
                                                @if($code->test)
                                                    <a 
                                                        href="{{ route('admin.tests.preview', $code->test) }}"
                                                        class="inline-flex items-center p-1 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-100 transition" 
                                                        title="Preview Test Blueprint"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="mt-1 text-[10px] font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                <span class="text-gray-400 dark:text-gray-500 uppercase tracking-widest text-[9px]">Target:</span>
                                                @if(($code->type ?? 'testing') === 'resource')
                                                    <a href="{{ $code->resource_url }}" target="_blank" class="text-blue-650 hover:underline max-w-[200px] truncate block" title="{{ $code->resource_url }}">
                                                        {{ $code->resource_url }}
                                                    </a>
                                                @else
                                                     @if($code->test)
                                                         <a href="{{ route('admin.tests.preview', $code->test) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-bold" title="Preview Test Blueprint">
                                                             {{ $code->test->name }}
                                                         </a>
                                                     @else
                                                         <span class="text-gray-800 dark:text-gray-200">N/A</span>
                                                     @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs text-gray-600 capitalize">
                                            {{ $code->type ?? 'testing' }}
                                        </td>

                                        <td class="px-4 py-2.5 whitespace-nowrap">
                                            @if($code->isValid())
                                                <form action="{{ route('admin.codes.expire-now', $code) }}" method="POST" class="inline" onsubmit="return confirm('Expire this access code immediately?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 hover:bg-green-100 text-green-700 hover:text-green-800 border border-green-100 transition shadow-sm cursor-pointer" title="Click to expire now">
                                                        Active
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.codes.extend-10-mins', $code) }}" method="POST" class="inline" onsubmit="return confirm('Extend this access code expiry by 10 minutes?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 border border-red-100 dark:border-red-900 transition shadow-sm cursor-pointer" title="Click to extend for 10 minutes">
                                                        Expired
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs text-gray-500">
                                            {{ $code->expires_at ? $code->expires_at->format('M d, Y h:i A') : 'Never' }}
                                        </td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-right text-xs font-medium">
                                            <form action="{{ route('admin.codes.destroy', $code) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this access code? Students will no longer be able to use it to join.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-950 bg-red-50 hover:bg-red-100 px-2 py-1 rounded-lg text-[10px] font-semibold transition" title="Delete">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if($codes->isNotEmpty() && $codes->every(fn($c) => !$c->isValid()))
                                    <tr x-show="!showExpired">
                                        <td colspan="5" class="px-6 py-12 text-center text-xs text-gray-500 bg-gray-50/30">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                <span>No active codes. Toggle <span class="font-bold text-gray-700">Show Expired</span> in the sidebar to view previous codes.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>
        </div>
    </div>
    <!-- Fullscreen Whiteboard Code Overlay -->
    <div id="whiteboard-modal" class="fixed inset-0 bg-slate-950/98 backdrop-blur-md z-[9999] hidden items-center justify-center transition-all duration-300">
        <button 
            onclick="closeFullscreenCode()" 
            class="absolute top-8 right-8 p-3 text-slate-400 hover:text-white hover:bg-white/10 rounded-full transition duration-200"
            aria-label="Close"
        >
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="text-center space-y-12 px-6 max-w-4xl mx-auto">
            <div class="space-y-4">
                <p class="text-indigo-400 text-lg sm:text-2xl font-black uppercase tracking-[0.2em] animate-pulse">Sesiune de Evaluare Activă</p>
                <h3 id="whiteboard-test-name" class="text-2xl sm:text-4xl font-extrabold text-white leading-tight">Test Blueprint</h3>
            </div>
            
            <div class="py-6">
                <!-- Extremely big bold code block -->
                <div id="whiteboard-code-display" class="font-mono font-black text-6xl sm:text-7xl md:text-8xl lg:text-[10rem] text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 tracking-[0.1em] pl-[0.1em] select-all leading-none py-8 drop-shadow-[0_10px_60px_rgba(99,102,241,0.25)]">
                    ------
                </div>
            </div>

            <div class="space-y-3">
                <p class="text-slate-400 text-base sm:text-xl font-semibold">Accesați site-ul și introduceți codul de mai sus pentru a începe:</p>
                <p id="whiteboard-url-display" class="text-indigo-300 text-lg sm:text-2xl font-bold font-mono tracking-wide select-all"></p>
            </div>
        </div>
    </div>

    <script>
        function showFullscreenCode(code, testName) {
            const modal = document.getElementById('whiteboard-modal');
            const codeDisplay = document.getElementById('whiteboard-code-display');
            const testNameDisplay = document.getElementById('whiteboard-test-name');
            const urlDisplay = document.getElementById('whiteboard-url-display');

            testNameDisplay.textContent = testName || 'Test Session';
            codeDisplay.textContent = code;
            urlDisplay.textContent = window.location.origin;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Optional: Request native fullscreen to maximize projector usage
            if (document.documentElement.requestFullscreen) {
                // Keep presentation clean
            }
        }

        function closeFullscreenCode() {
            const modal = document.getElementById('whiteboard-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close on ESC keypress
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFullscreenCode();
            }
        });
    </script>
</x-app-layout>
