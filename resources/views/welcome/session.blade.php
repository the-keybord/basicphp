<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $session->accessCode->test->name }} - Test Session</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col justify-between select-none">

    <!-- Floating Timer & Progress Header -->
    <header class="sticky top-0 bg-white border-b border-gray-150 shadow-sm z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center justify-between sm:justify-start gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="ZeceInfo Logo" class="h-8 w-auto object-contain">
                    <div class="h-6 w-[1px] bg-gray-250"></div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Student</span>
                        <span class="font-bold text-gray-800 text-sm md:text-base">{{ $session->firstname }} {{ $session->lastname }}</span>
                    </div>
                </div>
                
                <div id="timer-box" class="flex items-center space-x-3 px-5 py-2.5 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl transition duration-300">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="countdown-timer" class="font-mono text-xl font-black tracking-wider">--:--</span>
                </div>
            </div>

            <!-- Progress Bar inside header -->
            <div class="flex-1 max-w-xs sm:ml-auto">
                <div class="flex justify-between items-center mb-1 text-xxs font-bold text-gray-400 uppercase tracking-wider">
                    <span id="progress-text">Question 1 of {{ count($renderedQuestions) }}</span>
                </div>
                <div class="w-full bg-gray-150 h-2 rounded-full overflow-hidden">
                    <div id="progress-bar-fill" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-6 space-y-6">
        
        <!-- Navigator dots grid -->
        <div class="bg-white border border-gray-150 p-4 rounded-2xl shadow-sm flex flex-wrap gap-2.5 items-center justify-center">
            <span class="text-xxs font-bold text-gray-450 uppercase tracking-widest mr-2">Navigator:</span>
            @foreach($renderedQuestions as $index => $q)
                <button type="button" 
                        id="nav-dot-{{ $index }}" 
                        onclick="showQuestion({{ $index }})" 
                        class="nav-dot w-9 h-9 flex items-center justify-center border-2 border-gray-250 text-gray-650 rounded-xl font-bold text-sm transition hover:border-blue-400 hover:bg-blue-50/10">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>

        <form id="test-form" action="{{ route('test.submit', $session->token) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Question Cards -->
            @foreach($renderedQuestions as $index => $q)
                @php
                    $qModel = $q['model'];
                    $parsed = $q['parsed'];
                @endphp
                <div id="question-card-{{ $index }}" 
                     class="question-card bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden transition"
                     style="display: none;">
                    
                    <!-- Card Top Header -->
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">
                            Question {{ $index + 1 }} of {{ count($renderedQuestions) }}
                        </span>
                        <span class="text-xxs text-gray-400 uppercase font-semibold">
                            Type: {{ str_replace('_', ' ', $qModel->question_type) }}
                        </span>
                    </div>

                    <!-- Question Content -->
                    <div class="p-6 md:p-8 space-y-6">
                        <!-- Text description -->
                        <pre class="text-lg font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;"@if($qModel->question_type === 'drag_and_drop') id="dnd-text-{{ $qModel->id }}" @endif>{!! $parsed['text'] !!}</pre>

                        <!-- Image Diagram -->
                        @if(!empty($parsed['image']))
                            <div>
                                @php
                                    $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                                @endphp
                                <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm" style="max-width: 600px; height: auto;" alt="Question Diagram">
                            </div>
                        @endif

                        <!-- Input fields depending on type -->
                        <div class="pt-4 border-t border-gray-100">
                            
                            <!-- SINGLE SELECT -->
                            @if($qModel->question_type === 'singleselect')
                                <div class="space-y-3">
                                    @foreach($parsed['options'] as $option)
                                        <label class="flex items-center p-4 border border-gray-200 hover:border-blue-400 rounded-xl hover:bg-blue-50/10 cursor-pointer transition">
                                            <input type="radio" name="answers[{{ $qModel->id }}]" value="{{ $option }}" class="w-4 h-4 text-blue-600 border-gray-300">
                                            <span class="ml-3 text-gray-700 font-medium">{!! $option !!}</span>
                                        </label>
                                    @endforeach
                                </div>

                            <!-- MULTI-SELECT -->
                            @elseif($qModel->question_type === 'multiselect')
                                <div class="space-y-3">
                                    @foreach($parsed['options'] as $oIdx => $option)
                                        <label class="flex items-center p-4 border border-gray-200 hover:border-purple-400 rounded-xl hover:bg-purple-50/10 cursor-pointer transition">
                                            <input type="checkbox" name="answers[{{ $qModel->id }}][]" value="{{ $oIdx + 1 }}" class="w-4 h-4 text-purple-600 border-gray-300 rounded">
                                            <span class="ml-3 text-gray-700 font-medium">{!! $option !!}</span>
                                        </label>
                                    @endforeach
                                </div>

                            <!-- DROPDOWN -->
                            @elseif($qModel->question_type === 'dropdown')
                                @if(!empty($parsed['subjects']))
                                    <div class="space-y-4">
                                        @foreach($parsed['subjects'] as $sIndex => $subOptions)
                                            <div class="max-w-md">
                                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5 font-sans">Dropdown #{{ $sIndex + 1 }}</label>
                                                <select name="answers[{{ $qModel->id }}][]" class="block w-full border-gray-300 rounded-xl p-3.5 text-sm text-gray-700 font-medium bg-white">
                                                    <option value="" disabled selected>Choose answer...</option>
                                                    @foreach($subOptions as $option)
                                                        <option value="{{ strip_tags($option) }}">{!! strip_tags($option) !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="max-w-md">
                                        <select name="answers[{{ $qModel->id }}]" class="block w-full border-gray-300 rounded-xl p-3.5 text-sm text-gray-700 font-medium bg-white">
                                            <option value="" disabled selected>Choose answer...</option>
                                            @foreach($parsed['options'] as $option)
                                                <option value="{{ strip_tags($option) }}">{!! strip_tags($option) !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            <!-- TRUE/FALSE -->
                            @elseif($qModel->question_type === 'truefalse')
                                @if(!empty($parsed['subjects']))
                                    <div class="space-y-3">
                                        @foreach($parsed['subjects'] as $sIndex => $subject)
                                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50/50 gap-4">
                                                <div class="text-sm font-semibold text-gray-700">{!! $subject !!}</div>
                                                <div class="flex space-x-2">
                                                    <label class="flex items-center px-3.5 py-1.5 border border-gray-300 rounded-lg hover:bg-white text-xs font-bold text-gray-600 cursor-pointer select-none transition">
                                                        <input type="radio" name="answers[{{ $qModel->id }}][{{ $sIndex }}]" value="yes" class="w-3.5 h-3.5 text-teal-600 border-gray-300">
                                                        <span class="ml-1.5">Yes</span>
                                                    </label>
                                                    <label class="flex items-center px-3.5 py-1.5 border border-gray-300 rounded-lg hover:bg-white text-xs font-bold text-gray-600 cursor-pointer select-none transition">
                                                        <input type="radio" name="answers[{{ $qModel->id }}][{{ $sIndex }}]" value="no" class="w-3.5 h-3.5 text-red-650 border-gray-300">
                                                        <span class="ml-1.5">No</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="flex flex-col items-center justify-center p-4 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/10 cursor-pointer transition">
                                            <input type="radio" name="answers[{{ $qModel->id }}]" value="True" class="w-4 h-4 text-teal-600">
                                            <span class="mt-1 text-sm font-bold text-gray-700">True</span>
                                        </label>
                                        <label class="flex flex-col items-center justify-center p-4 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/10 cursor-pointer transition">
                                            <input type="radio" name="answers[{{ $qModel->id }}]" value="False" class="w-4 h-4 text-teal-600">
                                            <span class="mt-1 text-sm font-bold text-gray-700">False</span>
                                        </label>
                                    </div>
                                @endif

                            <!-- DRAG AND DROP -->
                            @elseif($qModel->question_type === 'drag_and_drop')
                                <div class="space-y-4">
                                    <p class="text-xs text-gray-500 italic">
                                        Drag tiles into the
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded border border-dashed border-amber-400 bg-amber-50 text-amber-700 font-bold text-xs mx-0.5">__ blank slots</span>
                                        above. Click a placed tile to return it.
                                    </p>
                                    <div class="p-4 border-2 border-dashed border-amber-200 rounded-xl bg-amber-50/20 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <p class="text-xs font-bold text-amber-500/80 uppercase tracking-widest">Answer Tiles</p>
                                                <button type="button" onclick="resetDndTiles({{ $qModel->id }})" class="px-2.5 py-1 bg-amber-200/60 hover:bg-amber-200 text-amber-900 font-bold text-xxs rounded-lg transition-all flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 11H18.21"/></svg>
                                                    Reset
                                                </button>
                                            </div>
                                            <div id="dnd-cancel-{{ $qModel->id }}"
                                                 style="display:none;"
                                                 class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-dashed border-red-300 bg-red-50 text-red-500 font-bold text-xs select-none transition-all">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Cancel
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2" id="dnd-pool-{{ $qModel->id }}">
                                            @foreach($parsed['options'] as $option)
                                                <div class="dnd-tile bg-white hover:bg-amber-50 border-2 border-amber-300 text-amber-900 font-bold px-4 py-2 rounded-lg text-sm cursor-grab active:cursor-grabbing shadow-sm select-none transition-all hover:shadow-md"
                                                     draggable="true">
                                                    {!! $option !!}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Dynamic hidden slots inputs container -->
                                    <div id="dnd-inputs-container-{{ $qModel->id }}"></div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Navigation Bar (Previous, Next, Submit buttons) -->
            <div class="bg-gray-800 rounded-xl p-4 flex justify-between items-center text-white shadow-md">
                <button type="button" 
                        id="prev-btn" 
                        onclick="prevQuestion()" 
                        class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition-all flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Previous
                </button>
                
                <button type="button" 
                        id="next-btn" 
                        onclick="nextQuestion()" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all flex items-center gap-1">
                    Next
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button type="submit" 
                        id="submit-btn" 
                        style="display: none;" 
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-md transition transform active:scale-95 flex items-center gap-1">
                    Submit Test
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </main>

    <!-- JS for Countdown Timer, Pagination, and Sync -->
    <script>
        // Countdown timer
        let remainingSeconds = {{ $remainingSeconds }};
        const countdownTimerEl = document.getElementById('countdown-timer');
        const timerBox = document.getElementById('timer-box');

        function updateTimerDisplay() {
            if (remainingSeconds <= 0) {
                countdownTimerEl.innerText = "00:00";
                document.getElementById('test-form').submit();
                return;
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            countdownTimerEl.innerText = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (remainingSeconds <= 60) {
                timerBox.className = "flex items-center space-x-3 px-5 py-2.5 bg-red-100 border border-red-200 text-red-700 rounded-xl transition duration-300 animate-pulse";
            } else if (remainingSeconds <= 300) {
                timerBox.className = "flex items-center space-x-3 px-5 py-2.5 bg-amber-100 border border-amber-200 text-amber-700 rounded-xl transition duration-300";
            }

            remainingSeconds--;
        }

        setInterval(updateTimerDisplay, 1000);
        updateTimerDisplay();


        // Test Pagination / Navigation Setup
        let activeQuestionIdx = 0;
        const totalQuestions = {{ count($renderedQuestions) }};

        function showQuestion(idx) {
            // Trigger auto-save immediately on transition
            triggerAutoSave();

            activeQuestionIdx = idx;

            // Show current card, hide others
            document.querySelectorAll('.question-card').forEach((card, i) => {
                card.style.display = (i === idx) ? 'block' : 'none';
            });

            // Update navigator buttons
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            if (prevBtn) {
                if (idx === 0) {
                    prevBtn.disabled = true;
                    prevBtn.classList.add('opacity-40', 'cursor-not-allowed');
                } else {
                    prevBtn.disabled = false;
                    prevBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                }
            }

            if (nextBtn && submitBtn) {
                if (idx === totalQuestions - 1) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-flex';
                } else {
                    nextBtn.style.display = 'inline-flex';
                    submitBtn.style.display = 'none';
                }
            }

            // Update nav-dots style
            document.querySelectorAll('.nav-dot').forEach((dot, i) => {
                dot.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'ring-2', 'ring-blue-200');
                if (i === idx) {
                    dot.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'ring-2', 'ring-blue-200');
                }
            });

            // Update progress text/bar
            const progressPercent = Math.round(((idx + 1) / totalQuestions) * 100);
            const progressBar = document.getElementById('progress-bar-fill');
            const progressText = document.getElementById('progress-text');
            if (progressBar) progressBar.style.width = progressPercent + '%';
            if (progressText) progressText.innerText = `Question ${idx + 1} of ${totalQuestions} (${progressPercent}% completed)`;

            updateNavDotStatus();
        }

        function nextQuestion() {
            if (activeQuestionIdx < totalQuestions - 1) {
                showQuestion(activeQuestionIdx + 1);
            }
        }

        function prevQuestion() {
            if (activeQuestionIdx > 0) {
                showQuestion(activeQuestionIdx - 1);
            }
        }

        function updateNavDotStatus() {
            const cards = document.querySelectorAll('.question-card');
            cards.forEach((card, i) => {
                const dot = document.getElementById(`nav-dot-${i}`);
                if (!dot) return;

                let answered = false;

                // check if checked options
                if (card.querySelector('input[type="radio"]:checked')) answered = true;
                if (card.querySelector('input[type="checkbox"]:checked')) answered = true;
                
                // check selects
                const selects = card.querySelectorAll('select');
                selects.forEach(sel => {
                    if (sel.value && sel.value !== '') answered = true;
                });

                // check DND slots
                const slots = card.querySelectorAll('.dnd-slot');
                if (slots.length > 0) {
                    let filled = 0;
                    slots.forEach(slot => {
                        if (slot.querySelector('.dnd-tile')) filled++;
                    });
                    if (filled > 0) answered = true;
                }

                if (answered && i !== activeQuestionIdx) {
                    dot.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
                    dot.classList.remove('bg-white', 'text-gray-650');
                } else if (!answered && i !== activeQuestionIdx) {
                    dot.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
                    dot.classList.add('bg-white', 'text-gray-650');
                }
            });
        }


        // Zero-Overhead Rate Limited Sync System
        let lastSaveTime = Date.now();
        let saveTimeout = null;
        let hasUnsavedChanges = false;

        function markChange() {
            hasUnsavedChanges = true;
            updateNavDotStatus();
            
            // Limit interactions to at most once per 30 seconds
            if (Date.now() - lastSaveTime > 30000) {
                triggerAutoSave();
            } else {
                if (!saveTimeout) {
                    const delay = 30000 - (Date.now() - lastSaveTime);
                    saveTimeout = setTimeout(() => {
                        triggerAutoSave();
                    }, delay);
                }
            }
        }

        function triggerAutoSave() {
            if (saveTimeout) {
                clearTimeout(saveTimeout);
                saveTimeout = null;
            }
            
            const form = document.getElementById('test-form');
            if (!form) return;
            
            const formData = new FormData(form);
            
            fetch("{{ route('test.session.autosave', $session->token, false) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                lastSaveTime = Date.now();
                hasUnsavedChanges = false;
                if (data.status === 'completed' || data.is_interrupted) {
                    showInterruptionAlert();
                }
            })
            .catch(err => console.error("Auto-save sync error:", err));
        }

        function showInterruptionAlert() {
            if (document.getElementById('interrupted-modal')) return;
            
            const modal = document.createElement('div');
            modal.id = "interrupted-modal";
            modal.className = "fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center z-[9999] p-4";
            modal.innerHTML = `
                <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl text-center space-y-5 border border-red-100">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto border border-red-100">
                        <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">Session Interrupted</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Your test session has been remotely locked and canceled by your instructor. 
                        You are being redirected back to the homepage.
                    </p>
                    <div class="pt-2">
                        <span class="inline-block px-5 py-2.5 bg-red-650 text-white font-bold rounded-xl shadow-md transition animate-pulse">
                            Redirecting...
                        </span>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 3000);
        }

        // Fallback idle sync every 2 minutes
        setInterval(() => {
            if (hasUnsavedChanges || (Date.now() - lastSaveTime > 110000)) {
                triggerAutoSave();
            }
        }, 120000);

        // Hook up listeners
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('test-form');
            if (form) {
                form.addEventListener('change', markChange);
                form.addEventListener('input', markChange);
            }
            
            // Show initial question
            showQuestion(0);
            updateNavDotStatus();
        });


        // Drag & drop logic
        let globalDragging = null;

        function resetDndTiles(qId) {
            const textEl = document.getElementById('dnd-text-' + qId);
            const pool = document.getElementById('dnd-pool-' + qId);
            if (!textEl || !pool) return;

            textEl.querySelectorAll('.dnd-slot').forEach(slot => {
                const tile = slot.querySelector('.dnd-tile');
                if (tile) {
                    tile.draggable = true;
                    tile.style.cursor = '';
                    tile.style.opacity = '1';
                    tile.title = '';
                    tile.onclick = null;

                    tile.addEventListener('dragstart', e => {
                        globalDragging = tile;
                        setTimeout(() => (tile.style.opacity = '0.4'), 0);
                        e.dataTransfer.effectAllowed = 'move';
                        const cancelZone = document.getElementById('dnd-cancel-' + qId);
                        if (cancelZone) cancelZone.style.display = 'flex';
                    });

                    pool.appendChild(tile);
                    slot.innerHTML = '&nbsp;?&nbsp;';

                    slot.style.minWidth    = '64px';
                    slot.style.height      = '26px';
                    slot.style.minHeight   = '';
                    slot.style.borderStyle = 'dashed';
                    slot.style.borderColor = '#f59e0b';
                    slot.style.background  = '#fffbeb';
                    slot.style.padding     = '0 8px';
                }
            });
            syncDndHiddenInputs(qId);
        }

        function syncDndHiddenInputs(qId) {
            const textEl = document.getElementById('dnd-text-' + qId);
            const container = document.getElementById('dnd-inputs-container-' + qId);
            if (!textEl || !container) return;

            container.innerHTML = '';
            textEl.querySelectorAll('.dnd-slot').forEach((slot, slotIdx) => {
                const tile = slot.querySelector('.dnd-tile');
                const val = tile ? tile.textContent.trim() : '';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${qId}][]`;
                input.value = val;
                container.appendChild(input);
            });

            markChange();
        }

        function initFillBlankDnD(textEl, pool, cancelZone, qId) {
            if (!textEl || !pool) return;
            let slotIdx = 0;

            textEl.innerHTML = textEl.innerHTML.replace(/__/g, () => {
                return `<span class="dnd-slot" data-slot="${slotIdx++}"
                              style="display:inline-flex;min-width:64px;height:26px;
                                     border:2px dashed #f59e0b;border-radius:6px;
                                     padding:0 8px;margin:0 3px;background:#fffbeb;
                                     vertical-align:middle;align-items:center;justify-content:center;
                                     color:#d97706;font-size:0.75rem;font-weight:700;
                                     transition:all 0.15s;cursor:pointer;"
                              >&nbsp;?&nbsp;</span>`;
            });

            function setupTile(tile) {
                tile.draggable = true;
                tile.style.cursor = '';
                tile.onclick = null;
                tile.addEventListener('dragstart', e => {
                    globalDragging = tile;
                    setTimeout(() => (tile.style.opacity = '0.4'), 0);
                    e.dataTransfer.effectAllowed = 'move';
                    if (cancelZone) cancelZone.style.display = 'flex';
                });
                tile.addEventListener('dragend', () => {
                    tile.style.opacity = '1';
                    globalDragging = null;
                    if (cancelZone) {
                        cancelZone.style.display = 'none';
                        cancelZone.style.borderColor = '#fca5a5';
                        cancelZone.style.background  = '#fef2f2';
                    }
                });
            }

            pool.querySelectorAll('.dnd-tile').forEach(setupTile);

            if (cancelZone) {
                cancelZone.addEventListener('dragover', e => {
                    e.preventDefault();
                    cancelZone.style.borderColor = '#ef4444';
                    cancelZone.style.background  = '#fee2e2';
                });
                cancelZone.addEventListener('dragleave', e => {
                    if (!cancelZone.contains(e.relatedTarget)) {
                        cancelZone.style.borderColor = '#fca5a5';
                        cancelZone.style.background  = '#fef2f2';
                    }
                });
                cancelZone.addEventListener('drop', e => {
                    e.preventDefault();
                    if (!globalDragging) return;
                    const parentSlot = globalDragging.closest('.dnd-slot');
                    if (parentSlot) {
                        sendToPool(globalDragging, parentSlot, true);
                    } else {
                        globalDragging.style.opacity = '1';
                        globalDragging.draggable = true;
                        globalDragging.onclick = null;
                        setupTile(globalDragging);
                        pool.appendChild(globalDragging);
                    }
                    globalDragging = null;
                    cancelZone.style.display = 'none';
                    syncDndHiddenInputs(qId);
                });
            }

            textEl.querySelectorAll('.dnd-slot').forEach(slot => {
                slot.addEventListener('dragover', e => {
                    e.preventDefault();
                    slot.style.borderColor = '#818cf8';
                    slot.style.borderStyle = 'solid';
                    slot.style.background  = '#eef2ff';
                });
                slot.addEventListener('dragleave', e => {
                    if (!slot.contains(e.relatedTarget)) {
                        if (!slot.querySelector('.dnd-tile')) resetSlotStyle(slot);
                    }
                });
                slot.addEventListener('drop', e => {
                    e.preventDefault();
                    if (!globalDragging) return;
                    const prev = slot.querySelector('.dnd-tile');
                    if (prev) sendToPool(prev, slot, false);

                    globalDragging.style.opacity = '1';
                    globalDragging.style.cursor  = 'pointer';
                    globalDragging.draggable     = false;
                    globalDragging.title         = 'Click to return';
                    globalDragging.onclick       = () => {
                        sendToPool(globalDragging, slot, true);
                        syncDndHiddenInputs(qId);
                    };

                    slot.innerHTML         = '';
                    slot.style.minWidth    = 'auto';
                    slot.style.borderStyle = 'solid';
                    slot.style.borderColor = '#818cf8';
                    slot.style.background  = '#eef2ff';
                    slot.style.padding     = '0 4px';
                    slot.style.height      = 'auto';
                    slot.style.minHeight   = '26px';
                    slot.appendChild(globalDragging);
                    globalDragging = null;

                    syncDndHiddenInputs(qId);
                });
            });

            function sendToPool(tile, slot, clearSlot) {
                tile.draggable    = true;
                tile.style.cursor = '';
                tile.style.opacity = '1';
                tile.title        = '';
                tile.onclick      = null;
                setupTile(tile);
                pool.appendChild(tile);
                if (clearSlot) {
                    slot.innerHTML = '&nbsp;?&nbsp;';
                    resetSlotStyle(slot);
                }
            }

            function resetSlotStyle(slot) {
                slot.style.minWidth    = '64px';
                slot.style.height      = '26px';
                slot.style.minHeight   = '';
                slot.style.borderStyle = 'dashed';
                slot.style.borderColor = '#f59e0b';
                slot.style.background  = '#fffbeb';
                slot.style.padding     = '0 8px';
            }

            // Sync initial state of slots into hidden inputs
            syncDndHiddenInputs(qId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialise DND elements
            document.querySelectorAll('[id^="dnd-text-"]').forEach(textEl => {
                const qId        = textEl.id.replace('dnd-text-', '');
                const pool       = document.getElementById('dnd-pool-' + qId);
                const cancelZone = document.getElementById('dnd-cancel-' + qId);
                initFillBlankDnD(textEl, pool, cancelZone, qId);
            });
        });
    </script>

</body>
</html>
