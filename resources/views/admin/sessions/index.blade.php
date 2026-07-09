<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Title Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-lg text-gray-800 leading-tight border-b border-gray-100 pb-3">
                        Exam Sessions
                    </h2>
                    
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.sessions.index') }}" class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Access Code</label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input 
                                    type="text" 
                                    name="code" 
                                    value="{{ request('code') }}" 
                                    placeholder="Filter by Code..." 
                                    class="block w-full pl-9 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
                                >
                            </div>
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button 
                                type="submit" 
                                class="flex-grow inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition"
                            >
                                Filter
                            </button>
                            @if(request()->filled('code'))
                                <a 
                                    href="{{ route('admin.sessions.index') }}" 
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold uppercase rounded-lg border border-gray-250 transition shadow-sm"
                                >
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Status Metadata Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Status Overview</span>
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Active Sessions:</span>
                            <span class="font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">{{ $sessions->whereNull('completed_at')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Completed Sessions:</span>
                            <span class="font-bold text-green-700 bg-green-50 border border-green-100 px-2 py-0.5 rounded">{{ $sessions->whereNotNull('completed_at')->count() }}</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Table area) -->
            <main class="flex-grow w-full min-w-0 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900">
                @if($sessions->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No active student sessions</h3>
                            <p class="mt-1 text-sm text-gray-500">Provide an access code to a student to start a session.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Test Blueprint</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Access Code</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status / Progress</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-4 py-2 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Emergency action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($sessions as $session)
                                        @php
                                            $isCompleted = !empty($session->completed_at);
                                            $testName = $session->accessCode->test->name ?? 'N/A';
                                            $duration = $session->accessCode->test->duration_minutes ?? 45;
                                            
                                            if (!$isCompleted) {
                                                $elapsed = now()->diffInMinutes($session->started_at);
                                                $remaining = max(0, $duration - $elapsed);
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <!-- Student Name -->
                                            <td class="px-4 py-2 whitespace-nowrap text-xs font-bold text-gray-900">
                                                {{ $session->firstname }} {{ $session->lastname }}
                                            </td>
                                            
                                            <!-- Test Blueprint -->
                                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-700 font-semibold">
                                                {{ $testName }}
                                            </td>

                                            <!-- Access Code -->
                                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                                <code class="px-1.5 py-0.5 bg-gray-100 border border-gray-250 text-gray-800 rounded font-mono text-[10px]">
                                                    {{ $session->accessCode->code ?? 'N/A' }}
                                                </code>
                                            </td>

                                            <!-- Status / Progress -->
                                            <td class="px-4 py-2 whitespace-nowrap text-[11px] text-gray-650">
                                                @if($isCompleted)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide bg-green-50 text-green-700 border border-green-150">
                                                        Completed
                                                    </span>
                                                    @if($session->is_interrupted)
                                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide bg-red-50 text-red-700 border border-red-150">
                                                            Interrupted
                                                        </span>
                                                    @endif
                                                    <div class="text-[9px] text-gray-400 mt-0.5">
                                                        Finished: {{ $session->completed_at->format('M d h:i A') }}
                                                    </div>
                                                @else
                                                    @if($session->isExpired())
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-150">
                                                            Expired
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-150 animate-pulse">
                                                            Writing
                                                        </span>
                                                        <div class="text-[9px] text-gray-500 mt-0.5">
                                                            {{ $elapsed }}m elapsed | <strong class="text-blue-600">{{ $remaining }}m left</strong>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>

                                            <!-- Score -->
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($isCompleted)
                                                    @php
                                                        $percentage = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                                                        $scoreColor = $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-amber-600' : 'text-red-600');
                                                    @endphp
                                                    <span class="text-xs font-black {{ $scoreColor }}">
                                                        {{ $session->score }} <span class="text-[10px] text-gray-400">/ {{ $session->total_questions }}</span>
                                                    </span>
                                                    <span class="text-[9px] text-gray-450 block font-bold">({{ $percentage }}%)</span>
                                                @else
                                                    <span class="text-xs font-semibold text-gray-400">--</span>
                                                @endif
                                            </td>

                                            <!-- Emergency Actions -->
                                            <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                                @if(!$isCompleted)
                                                    <form action="{{ route('admin.sessions.interrupt', $session) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to terminate {{ $session->firstname }}\'s session remotely? This will force submit and grade all their currently answered questions.');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 hover:border-red-300 rounded-md text-[10px] font-bold transition shadow-sm">
                                                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                            Force Submit
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.sessions.review', $session) }}" class="inline-flex items-center px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 hover:border-blue-300 rounded-md text-[10px] font-bold transition shadow-sm">
                                                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Review
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            @endif
            </main>
        </div>
    </div>
</x-app-layout>
