<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Student Exam Sessions') }}
            </h2>
            <div class="flex space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                    Active: {{ $sessions->whereNull('completed_at')->count() }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                    Completed: {{ $sessions->whereNotNull('completed_at')->count() }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
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
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Test Blueprint</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Access Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status / Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Emergency action</th>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $session->firstname }} {{ $session->lastname }}
                                            </td>
                                            
                                            <!-- Test Blueprint -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                                {{ $testName }}
                                            </td>

                                            <!-- Access Code -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <code class="px-2 py-1 bg-gray-100 border border-gray-250 text-gray-800 rounded font-mono text-xs">
                                                    {{ $session->accessCode->code ?? 'N/A' }}
                                                </code>
                                            </td>

                                            <!-- Status / Progress -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                                @if($isCompleted)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">
                                                        Completed
                                                    </span>
                                                    @if($session->is_interrupted)
                                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">
                                                            Interrupted
                                                        </span>
                                                    @endif
                                                    <div class="text-xxs text-gray-400 mt-1">
                                                        Finished: {{ $session->completed_at->format('M d h:i A') }}
                                                    </div>
                                                @else
                                                    @if($session->isExpired())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                                            Time Expired
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">
                                                            Writing
                                                        </span>
                                                        <div class="text-xxs text-gray-500 mt-1">
                                                            {{ $elapsed }} min elapsed | <strong class="text-blue-600">{{ $remaining }} min left</strong>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>

                                            <!-- Score -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($isCompleted)
                                                    @php
                                                        $percentage = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                                                        $scoreColor = $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-amber-600' : 'text-red-600');
                                                    @endphp
                                                    <span class="text-sm font-black {{ $scoreColor }}">
                                                        {{ $session->score }} <span class="text-xs text-gray-400">/ {{ $session->total_questions }}</span>
                                                    </span>
                                                    <span class="text-xxs text-gray-450 block font-semibold">({{ $percentage }}%)</span>
                                                @else
                                                    <span class="text-sm font-semibold text-gray-400">--</span>
                                                @endif
                                            </td>

                                            <!-- Emergency Actions -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if(!$isCompleted)
                                                    <form action="{{ route('admin.sessions.interrupt', $session) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to terminate {{ $session->firstname }}\'s session remotely? This will force submit and grade all their currently answered questions.');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 hover:border-red-300 rounded-lg text-xs font-bold transition shadow-sm">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                            Force Submit
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No actions</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
