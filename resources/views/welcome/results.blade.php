<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Results - {{ $session->accessCode->test->name }}</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <link rel="icon" type="image/png" href="{{ asset('images/zeceinfoblock.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .bg-white {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .dark body {
            background-color: #0f1015 !important;
            color: #f1f5f9 !important;
        }
        .dark .bg-white {
            background-color: #161720 !important;
            border-color: #232535 !important;
            color: #f1f5f9 !important;
        }
        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-gray-700 {
            color: #f1f5f9 !important;
        }
        .dark .text-gray-650,
        .dark .text-gray-600,
        .dark .text-gray-500 {
            color: #94a3b8 !important;
        }
        .dark header {
            background-color: #161720 !important;
            border-bottom: 1px solid #232535 !important;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased min-h-screen flex flex-col justify-between">

    <header class="w-full p-6 flex justify-between items-center max-w-4xl mx-auto">
        <div class="flex items-center gap-2 logo-toggle-trigger cursor-pointer">
            <img src="{{ asset('images/zeceinfoblock.png') }}" alt="ZeceInfo Logo" class="h-8 w-auto object-contain">
        </div>
        <div class="flex items-center space-x-4">
            <button type="button" class="logo-toggle-trigger p-2 text-slate-500 hover:text-slate-900 dark:text-neutral-400 dark:hover:text-white transition-colors duration-200 focus:outline-none" aria-label="Toggle Theme">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">Return Home</a>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-8 space-y-8">
        
        <!-- Score Overview Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 p-8 text-center space-y-4">
            <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-100 rounded-full text-xxs font-bold uppercase tracking-widest block w-max mx-auto">
                Test Completed
            </span>
            
            <h1 class="text-3xl font-black text-gray-800 leading-tight">
                {{ $session->accessCode->test->name }}
            </h1>
            
            <p class="text-gray-500 font-medium">
                Student: <span class="text-gray-800 font-bold">{{ $session->firstname }} {{ $session->lastname }}</span>
            </p>

            @if($viewGrade)
                <div class="py-6 max-w-xs mx-auto">
                    @php
                        $percentage = $session->total_questions > 0 ? round(($session->score / $session->total_questions) * 100) : 0;
                        $scoreColor = $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600');
                        $bgColor = $percentage >= 70 ? 'bg-green-50' : ($percentage >= 50 ? 'bg-yellow-50' : 'bg-red-50');
                    @endphp
                    <div class="rounded-2xl {{ $bgColor }} p-6 border border-gray-100">
                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Your Score</span>
                        <div class="text-5xl font-black {{ $scoreColor }}">
                            {{ $session->score }} <span class="text-2xl text-gray-400">/ {{ $session->total_questions }}</span>
                        </div>
                        <div class="text-sm font-bold text-gray-500 mt-1">
                            Grade: {{ $percentage }}%
                        </div>
                    </div>
                </div>
            @else
                <div class="py-6 max-w-md mx-auto">
                    <div class="rounded-2xl bg-blue-50/50 p-6 border border-blue-100 text-blue-800 dark:text-blue-300">
                        <span class="text-xxs font-bold text-blue-400 uppercase tracking-widest block mb-1">Results Status</span>
                        <div class="text-lg font-bold">
                            Graded successfully
                        </div>
                        <div class="text-xs text-blue-600 dark:text-blue-450 mt-1">
                            Your score and grade are hidden per the test configuration.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if($hideDetails)
            <!-- Responses Hidden Alert -->
            <div class="bg-blue-50 border border-blue-150 rounded-2xl p-8 text-center max-w-xl mx-auto space-y-3">
                <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-bold text-blue-900">Details are hidden</h3>
                <p class="text-sm text-blue-700 leading-relaxed">
                    Your test session has been submitted successfully. Details and correct answers have been hidden by your instructor.
                </p>
            </div>
        @else
            <!-- Question Review Sheet -->
            @if($viewAnswers)
                <div class="space-y-6">
                    <h2 class="text-xl font-extrabold text-gray-900 px-1">Review Question Sheet</h2>
                    
                    @foreach($renderedQuestions as $index => $q)
                        @php
                            $qModel = $q['model'];
                            $parsed = $q['parsed'];
                            $studentAnswer = $session->answers[$qModel->id] ?? '';
                            $correctAnswer = $qModel->correct_answer_string ?? '';
                             
                            $isCorrect = false;
                            $cleanedUser = html_entity_decode(strtolower(trim($studentAnswer)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $cleanedCorrect = html_entity_decode(strtolower(trim($correctAnswer)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            if ($cleanedUser === $cleanedCorrect && trim($correctAnswer) !== '') {
                                $isCorrect = true;
                            }
                            
                            $cardBorder = $isCorrect ? 'border-green-300' : 'border-red-300';
                            $statusBg = $isCorrect ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100';
                        @endphp
                        
                        <div class="bg-white rounded-2xl border-2 {{ $cardBorder }} shadow-sm overflow-hidden">
                            <!-- Card Header -->
                            <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    Question {{ $index + 1 }}
                                </span>
                                <span class="px-2.5 py-0.5 border rounded-full text-xxs font-bold uppercase tracking-wider {{ $statusBg }}">
                                    {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-6 md:p-8 space-y-6">
                                <!-- Text -->
                                <pre class="text-lg font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;">{!! $parsed['text'] !!}</pre>

                                <!-- Image Diagram -->
                                @if(!empty($parsed['image']))
                                    <div>
                                        @php
                                            $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm" style="max-width: 600px; height: auto;" alt="Question Diagram">
                                    </div>
                                @endif

                                <!-- Responses details -->
                                <div class="pt-4 border-t border-gray-100 space-y-3">
                                    <!-- Student Answer -->
                                    <div>
                                        <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Your Answer</span>
                                        <div class="px-4 py-3 rounded-lg border font-medium text-sm {{ $isCorrect ? 'bg-green-50/50 border-green-200 text-green-900' : 'bg-red-50/50 border-red-200 text-red-900' }}">
                                            {{ $studentAnswer ?: '(No answer selected)' }}
                                        </div>
                                    </div>

                                    <!-- Correct Answer (Only shown if viewCorrect is true and student answered incorrectly) -->
                                    @if($viewCorrect && !$isCorrect)
                                        <div>
                                            <span class="text-xxs font-bold text-gray-400 uppercase tracking-widest block mb-1">Correct Answer</span>
                                            <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-900 rounded-lg font-bold text-sm">
                                                {{ $correctAnswer }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </main>

    <footer class="w-full text-center py-6 text-xs text-gray-400">
        &copy; {{ date('Y') }} ZeceInfo. All rights reserved.
    </footer>

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
