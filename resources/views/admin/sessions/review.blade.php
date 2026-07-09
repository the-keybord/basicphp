<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Back Button & Student Name Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                        <a href="{{ route('admin.sessions.index') }}" class="text-gray-600 hover:text-gray-900 transition p-1.5 hover:bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <h2 class="font-bold text-base text-gray-800 leading-tight">
                            Review Session
                        </h2>
                    </div>
                    
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Student</span>
                        <h3 class="text-lg font-black text-blue-600 capitalize leading-tight">
                            {{ $session->firstname }} {{ $session->lastname }}
                        </h3>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        <span class="text-gray-500">Status:</span>
                        @if($session->is_interrupted)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-bold bg-red-50 text-red-700 border border-red-100">
                                Interrupted
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-bold bg-green-50 text-green-700 border border-green-100">
                                Completed
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Test Details Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Test Details</span>
                    <div class="space-y-3">
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-bold text-gray-450 uppercase block">Blueprint</span>
                            <span class="text-sm font-bold text-gray-900 block leading-snug">{{ $session->accessCode->test->name ?? 'N/A' }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-bold text-gray-450 uppercase block">Started</span>
                            <span class="text-xs text-gray-700 block">{{ $session->started_at->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($session->completed_at)
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-bold text-gray-455 uppercase block">Submitted</span>
                                <span class="text-xs text-gray-700 block">{{ $session->completed_at->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Score / Grade Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Grading Summary</span>
                    @php
                        $percentage = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                        $scoreColor = $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-amber-600' : 'text-red-600');
                        $scoreBg = $percentage >= 70 ? 'bg-green-50/50 border-green-100' : ($percentage >= 50 ? 'bg-amber-50/50 border-amber-100' : 'bg-red-50/50 border-red-100');
                    @endphp
                    <div class="rounded-xl p-4 border flex flex-col justify-center items-center text-center {{ $scoreBg }}">
                        <span class="text-[10px] font-bold text-gray-455 uppercase tracking-widest block mb-1">Total Score</span>
                        <div class="text-3xl font-black {{ $scoreColor }}">
                            {{ $session->score ?? 0 }} <span class="text-sm text-gray-400">/ {{ $session->total_questions }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500 mt-1 uppercase">Grade: {{ $percentage }}%</span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        <span class="text-gray-500">Duration:</span>
                        <span class="font-bold text-gray-800">
                            @if($session->completed_at)
                                {{ $session->started_at->diffInMinutes($session->completed_at) }} mins
                            @else
                                {{ $session->started_at->diffInMinutes(now()) }} mins
                            @endif
                        </span>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Graded Question Sheet) -->
            <main class="flex-grow w-full min-w-0 space-y-6">
                <!-- Responses Sheet Title -->
                <div class="flex items-center justify-between bg-white rounded-xl border border-gray-150 px-5 py-3.5 shadow-sm">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider">Graded Question Sheet</h3>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ count($renderedQuestions) }} Questions</span>
                </div>

                <!-- Graded Cards -->
            <div class="space-y-6">
                @foreach($renderedQuestions as $index => $q)
                    @php
                        $qModel = $q['model'];
                        $parsed = $q['parsed'];
                        $studentAnswer = $session->answers[$qModel->id] ?? '';
                        $correctAnswer = $qModel->correct_answer_string ?? '';
                             
                        $isEmpty = trim((string)$studentAnswer) === '';
                        $isCorrect = false;
                             
                        $cleanedUser = html_entity_decode(strtolower(trim($studentAnswer)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $cleanedCorrect = html_entity_decode(strtolower(trim($correctAnswer)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                             
                        if (!$isEmpty && $cleanedUser === $cleanedCorrect && trim($correctAnswer) !== '') {
                             $isCorrect = true;
                        }
                        
                        $cardBorder = $isEmpty ? 'border-gray-200' : ($isCorrect ? 'border-green-300' : 'border-red-300');
                        $statusBg = $isEmpty ? 'bg-gray-50 text-gray-700 border-gray-200' : ($isCorrect ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200');
                    @endphp
                    
                    <div class="bg-white rounded-xl border-2 {{ $cardBorder }} shadow-sm overflow-hidden">
                        <!-- Card Header -->
                        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Question {{ $index + 1 }}
                                </span>
                                <span class="text-xxs text-gray-400 font-semibold px-2 py-0.5 bg-gray-100 rounded border border-gray-200 uppercase">
                                    {{ str_replace('_', ' ', $qModel->question_type) }}
                                </span>
                                <span class="text-xxs text-gray-400 font-semibold px-2 py-0.5 bg-gray-100 rounded border border-gray-200 uppercase">
                                    ID: {{ $qModel->id }}
                                </span>
                                <a href="{{ route('admin.questions.preview', $qModel) }}" target="_blank" class="inline-flex items-center px-2 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded text-xxs font-semibold transition" title="Preview Question">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview
                                </a>
                            </div>
                            <span class="px-3 py-1 border rounded-full text-xxs font-black uppercase tracking-wider {{ $statusBg }}">
                                @if($isEmpty)
                                    No Answer
                                @elseif($isCorrect)
                                    Correct
                                @else
                                    Incorrect
                                @endif
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-6 md:p-8 space-y-6">
                            <!-- Question Text -->
                            <pre class="text-base font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;">{!! $parsed['text'] !!}</pre>

                            <!-- Image Diagram -->
                            @if(!empty($parsed['image']))
                                <div class="pt-2">
                                    @php
                                        $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                                    @endphp
                                    <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm" style="max-width: 500px; height: auto;" alt="Question Diagram">
                                </div>
                            @endif

                            <!-- Responses Details Grid -->
                            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Student Answer -->
                                <div>
                                    <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Student's Answer</span>
                                    <div class="px-4 py-3 rounded-lg border font-medium text-sm {{ $isEmpty ? 'bg-gray-50 border-gray-200 text-gray-500' : ($isCorrect ? 'bg-green-50/50 border-green-200 text-green-900' : 'bg-red-50/50 border-red-200 text-red-900') }}">
                                        {{ str_replace('#', ', ', $studentAnswer) ?: '(No answer selected)' }}
                                    </div>
                                </div>

                                <!-- Correct Answer Key -->
                                <div>
                                    <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Correct Key</span>
                                    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-950 rounded-lg font-bold text-sm">
                                        {{ str_replace('#', ', ', $correctAnswer) ?: '(No correct answer configured)' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
