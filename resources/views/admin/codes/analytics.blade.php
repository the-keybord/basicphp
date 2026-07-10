<x-app-layout title="Analytics: {{ $code->code }}">
    @php
        $avgPct = $totalQuestions > 0 ? round(($avgScore / $totalQuestions) * 100) : 0;
        $avgScoreColor = $avgPct >= 70 ? 'text-green-600' : ($avgPct >= 50 ? 'text-amber-600' : 'text-red-600');
    @endphp

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Back & Code Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                        <a href="{{ route('admin.codes.index') }}" class="text-gray-600 hover:text-gray-900 transition p-1.5 hover:bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <h2 class="font-bold text-base text-gray-800 leading-tight">
                            Code Analytics
                        </h2>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Access Code</span>
                        <span class="font-mono font-black text-2xl tracking-wider text-blue-600 bg-blue-50/50 px-3 py-1.5 rounded-lg border border-blue-100 block text-center select-all">
                            {{ $code->code }}
                        </span>
                    </div>
                </div>

                <!-- Aggregate Statistics -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Overview stats</span>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Total sessions:</span>
                            <span class="font-bold text-gray-800 bg-gray-50 px-2.5 py-0.5 rounded border border-gray-150">{{ $sessions->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Average score:</span>
                            <span class="font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-100">
                                {{ $avgScore }} <span class="text-[10px] text-gray-400">/ {{ $totalQuestions }}</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Average percentage:</span>
                            <span class="font-bold {{ $avgScoreColor }} bg-gray-50 px-2.5 py-0.5 rounded border border-gray-150">
                                {{ $avgPct }}%
                            </span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Matrix & Analytics) -->
            <main class="flex-grow w-full min-w-0 space-y-6">
                <!-- Header/Title -->
                <div class="flex items-center justify-between bg-white rounded-xl border border-gray-150 px-5 py-3.5 shadow-sm">
                    <div class="space-y-0.5">
                        <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Performance Heatmap</h3>
                        <span class="text-xxs text-gray-400 font-bold block">Blueprint: {{ $code->test->name ?? 'N/A' }}</span>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-100">{{ $sessions->count() }} Completed Sessions</span>
                </div>

                <!-- Matrix Heatmap Table Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-50 pb-2">Student Mistakes Matrix</h4>
                    
                    @if($sessions->isEmpty())
                        <div class="text-center py-12 text-sm text-gray-500">
                            <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            No student responses recorded yet for this code.
                        </div>
                    @else
                        <div class="overflow-x-auto" onwheel="if (event.deltaY !== 0) { event.preventDefault(); this.scrollLeft += event.deltaY; }">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-20 border-r border-gray-200/80 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.06)]">Student Name</th>
                                        <th class="px-3 py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Score</th>
                                        @foreach($orderedQuestions as $index => $q)
                                            <th class="px-2 py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                <a href="{{ route('admin.tests.preview', $code->test) }}#question-{{ $q->id }}" target="_blank" class="hover:text-blue-650 hover:underline transition" title="Preview Question (ID: {{ $q->id }})">
                                                    Q{{ $index + 1 }}
                                                </a>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($sessions as $session)
                                        @php
                                            $pct = $totalQuestions > 0 ? round(($session->score / $totalQuestions) * 100) : 0;
                                            $scoreCol = $pct >= 70 ? 'text-green-600' : ($pct >= 50 ? 'text-amber-600' : 'text-red-600');
                                        @endphp
                                        <tr class="group hover:bg-gray-50/50 transition">
                                            <td class="px-3 py-2.5 whitespace-nowrap text-xs font-bold text-gray-800 capitalize sticky left-0 bg-white group-hover:bg-gray-50/80 transition z-10 border-r border-gray-200/80 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.06)]">
                                                <a href="{{ route('admin.sessions.review', $session) }}" class="hover:text-blue-600 hover:underline">
                                                    {{ $session->firstname }} {{ $session->lastname }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2.5 whitespace-nowrap text-center text-xs font-black {{ $scoreCol }}">
                                                {{ $session->score }}<span class="text-[10px] text-gray-400">/{{ $totalQuestions }}</span>
                                            </td>
                                            @foreach($orderedQuestions as $index => $q)
                                                @php
                                                    $ans = $session->answers[$q->id] ?? '';
                                                    $correct = $q->correct_answer_string ?? '';
                                                    
                                                    $isEmpty = trim((string)$ans) === '';
                                                    $isCorrect = false;
                                                    
                                                    $cleanedUser = html_entity_decode(strtolower(trim($ans)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                                    $cleanedCorrect = html_entity_decode(strtolower(trim($correct)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                                    
                                                    if (!$isEmpty && $cleanedUser === $cleanedCorrect && trim($correct) !== '') {
                                                         $isCorrect = true;
                                                    }
                                                    
                                                    $cellBg = $isEmpty ? 'bg-gray-50 text-gray-400 border-gray-200 hover:bg-gray-100' : ($isCorrect ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100/70' : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100/70');
                                                    $tooltipText = "Q" . ($index + 1) . ": " . ($isCorrect ? 'Correct' : ($isEmpty ? 'Unanswered' : 'Incorrect (User: ' . str_replace('#', ', ', $ans) . ' | Key: ' . str_replace('#', ', ', $correct) . ')'));
                                                @endphp
                                                <td class="px-2 py-2.5 whitespace-nowrap text-center">
                                                    <span 
                                                        class="w-6.5 h-6.5 min-w-[26px] min-h-[26px] inline-flex items-center justify-center text-[10px] font-bold rounded border transition {{ $cellBg }}"
                                                        title="{{ $tooltipText }}"
                                                    >
                                                        @if($isEmpty)
                                                            -
                                                        @elseif($isCorrect)
                                                            ✓
                                                        @else
                                                            ✗
                                                        @endif
                                                    </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Questions Difficulty / Analytics Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900 space-y-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">Questions Difficulty Analytics</h4>
                    
                    @if($sessions->isEmpty())
                        <div class="text-center py-4 text-sm text-gray-500">
                            No data available to compute stats.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left: List Sorted by difficulty -->
                            <div class="space-y-4">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Sorted by Difficulty (Success Rate)</span>
                                <div class="space-y-3">
                                    @foreach($troublesomeQuestions as $stat)
                                        @php
                                            $successCol = $stat['success_rate'] >= 70 ? 'text-green-700 bg-green-50 border-green-150' : ($stat['success_rate'] >= 50 ? 'text-amber-700 bg-amber-50 border-amber-150' : 'text-red-700 bg-red-50 border-red-150');
                                        @endphp
                                        <div class="flex items-center justify-between p-3 rounded-xl border border-gray-150 bg-gray-50/50 hover:bg-gray-50 transition text-xs">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-6 h-6 inline-flex items-center justify-center bg-white border border-gray-250 rounded font-bold text-gray-700">Q{{ $stat['index'] }}</span>
                                                <span class="text-gray-500 font-medium">ID: {{ $stat['model']->id }}</span>
                                                <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-400">
                                                    {{ str_replace('_', ' ', $stat['model']->question_type) }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <span class="font-black text-[10px] px-2.5 py-0.5 border rounded-full {{ $successCol }}">
                                                    {{ $stat['success_rate'] }}% Success
                                                </span>
                                                <a href="{{ route('admin.tests.preview', $code->test) }}#question-{{ $stat['model']->id }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition" title="Preview Question inside Test">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right: Detailed Breakdowns -->
                            <div class="space-y-4">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Question-by-Question breakdown</span>
                                <div class="space-y-4">
                                    @foreach($questionAnalytics as $stat)
                                        <div class="space-y-1.5 p-3.5 rounded-xl border border-gray-150">
                                            <div class="flex items-center justify-between text-xs font-bold">
                                                <a href="{{ route('admin.tests.preview', $code->test) }}#question-{{ $stat['model']->id }}" target="_blank" class="text-gray-800 hover:text-blue-650 hover:underline transition" title="Preview Question (ID: {{ $stat['model']->id }})">
                                                    Question {{ $stat['index'] }} <span class="text-[10px] font-medium text-gray-400 font-mono">(#{{ $stat['model']->id }})</span>
                                                </a>
                                                <span class="text-blue-600 font-extrabold">{{ $stat['success_rate'] }}% Correct</span>
                                            </div>
                                            <!-- Stacked progress bar -->
                                            <div class="w-full h-2 rounded-full overflow-hidden flex bg-gray-100">
                                                @if($stat['correct'] > 0)
                                                    <div class="bg-green-500 h-full" style="width: {{ ($stat['correct'] / $sessions->count()) * 100 }}%" title="Correct: {{ $stat['correct'] }}"></div>
                                                @endif
                                                @if($stat['incorrect'] > 0)
                                                    <div class="bg-red-500 h-full" style="width: {{ ($stat['incorrect'] / $sessions->count()) * 100 }}%" title="Incorrect: {{ $stat['incorrect'] }}"></div>
                                                @endif
                                                @if($stat['empty'] > 0)
                                                    <div class="bg-gray-400 h-full" style="width: {{ ($stat['empty'] / $sessions->count()) * 100 }}%" title="Unanswered: {{ $stat['empty'] }}"></div>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-[9px] text-gray-450 font-bold uppercase tracking-wider">
                                                <span class="text-green-600">{{ $stat['correct'] }} Correct</span>
                                                <span class="text-red-500">{{ $stat['incorrect'] }} Incorrect</span>
                                                <span class="text-gray-500">{{ $stat['empty'] }} Empty</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
