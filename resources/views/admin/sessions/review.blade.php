<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.sessions.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Review Session: <span class="text-blue-600">{{ $session->firstname }} {{ $session->lastname }}</span>
                </h2>
            </div>
            <div class="flex space-x-2">
                @if($session->is_interrupted)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        Interrupted
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        Completed
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Session Overview Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-150 p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block">Test Blueprint</span>
                        <h1 class="text-xl font-bold text-gray-900">{{ $session->accessCode->test->name ?? 'N/A' }}</h1>
                        <p class="text-sm text-gray-500 font-medium">
                            Joined: {{ $session->started_at->format('M d, Y h:i A') }}
                        </p>
                        @if($session->completed_at)
                            <p class="text-sm text-gray-500 font-medium">
                                Submitted: {{ $session->completed_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col justify-center items-center text-center">
                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Time Elapsed</span>
                        <div class="text-lg font-bold text-gray-800">
                            @if($session->completed_at)
                                {{ $session->started_at->diffInMinutes($session->completed_at) }} mins
                            @else
                                {{ $session->started_at->diffInMinutes(now()) }} mins (Active)
                            @endif
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 flex flex-col justify-center items-center text-center">
                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Total Score</span>
                        @php
                            $percentage = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                            $scoreColor = $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-amber-600' : 'text-red-600');
                        @endphp
                        <div class="text-3xl font-black {{ $scoreColor }}">
                            {{ $session->score ?? 0 }} <span class="text-sm text-gray-400">/ {{ $session->total_questions }}</span>
                        </div>
                        <span class="text-xxs font-bold text-gray-500 mt-1 uppercase">Grade: {{ $percentage }}%</span>
                    </div>
                </div>
            </div>

            <!-- Responses Sheet Title -->
            <div class="flex items-center justify-between px-1">
                <h3 class="text-lg font-bold text-gray-900">Graded Question Sheet</h3>
                <span class="text-xs text-gray-500 font-medium">{{ count($renderedQuestions) }} Questions</span>
            </div>

            <!-- Question Review List -->
            <div class="space-y-6">
                @foreach($renderedQuestions as $index => $q)
                    @php
                        $qModel = $q['model'];
                        $parsed = $q['parsed'];
                        $studentAnswer = $session->answers[$qModel->id] ?? '';
                        $correctAnswer = $qModel->correct_answer_string ?? '';
                        
                        $isCorrect = false;
                        $isEmpty = empty(trim($studentAnswer));
                        
                        if (!$isEmpty && strtolower(trim($studentAnswer)) === strtolower(trim($correctAnswer)) && trim($correctAnswer) !== '') {
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
                                        {{ $studentAnswer ?: '(No answer selected)' }}
                                    </div>
                                </div>

                                <!-- Correct Answer Key -->
                                <div>
                                    <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Correct Key</span>
                                    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-950 rounded-lg font-bold text-sm">
                                        {{ $correctAnswer ?: '(No correct answer configured)' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
