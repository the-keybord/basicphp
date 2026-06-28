<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Step 2: Configure Correct Answer') }}
                </h2>
            </div>
            
            <div class="flex space-x-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-bold uppercase tracking-wider">
                    ID: #{{ $question->id }}
                </span>
                @php
                    $badgeColors = [
                        'multiselect' => 'bg-purple-50 text-purple-700 border-purple-100',
                        'singleselect' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                        'dropdown' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'drag_and_drop' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'truefalse' => 'bg-teal-50 text-teal-700 border-teal-100',
                    ];
                    $color = $badgeColors[$question->question_type] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                    $formattedType = str_replace('_', ' ', $question->question_type);
                @endphp
                <span class="px-3 py-1 border rounded-full text-xs font-semibold capitalize {{ $color }}">
                    {{ $formattedType }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Instructions and Live Answer Container Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg text-white p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div class="md:col-span-2 space-y-2">
                        <h3 class="text-lg font-bold">Interactive Answer Configurator</h3>
                        <p class="text-sm text-blue-100 leading-relaxed">
                            Interact with the question mockup below just as a student would. The system will capture your selections and generate the correctly formatted answer string automatically.
                        </p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 flex flex-col justify-between">
                        <span class="text-xxs font-bold text-blue-200 uppercase tracking-widest block mb-1">Answer Container</span>
                        <div id="answer-display-text" class="text-base font-black truncate text-amber-300">
                            {{ $question->correct_answer_string ?: 'No selection made yet...' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form wrapper around the mockup rendering -->
            <form id="answer-form" action="{{ route('admin.questions.store-answer', $question) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="correct_answer_string" id="correct_answer_string" value="{{ $question->correct_answer_string }}">

                <!-- Main Question Mockup -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Question Text</span>
                        
                        <!-- Question Text -->
                        <pre class="text-xl font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;"@if($question->question_type === 'drag_and_drop') id="dnd-question-text" @endif>{!! $parsed['text'] !!}</pre>

                        <!-- Question Image (if any) -->
                        @if(!empty($parsed['image']))
                            <div class="mt-4">
                                @php
                                    $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                                @endphp
                                <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm animate-fade-in" style="max-width: 600px; height: auto; display: block;" alt="Question diagram">
                            </div>
                        @endif
                    </div>

                    <div class="p-8">
                        <!-- Interactive choices based on Question Type -->

                        <!-- SINGLE SELECT -->
                        @if($question->question_type === 'singleselect')
                            <div class="space-y-3">
                                @foreach($parsed['options'] as $index => $option)
                                    <label class="flex items-center p-4 border border-gray-200 hover:border-blue-400 rounded-xl hover:bg-blue-50/20 cursor-pointer transition duration-150 group">
                                        <input type="radio" name="choice" value="{{ $index }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 transition">
                                        <span class="ml-3.5 text-gray-700 font-medium group-hover:text-gray-900 transition">{!! $option !!}</span>
                                    </label>
                                @endforeach
                            </div>

                        <!-- MULTI-SELECT -->
                        @elseif($question->question_type === 'multiselect')
                            <div class="space-y-3">
                                @foreach($parsed['options'] as $index => $option)
                                    <label class="flex items-center p-4 border border-gray-200 hover:border-purple-400 rounded-xl hover:bg-purple-50/20 cursor-pointer transition duration-150 group">
                                        <input type="checkbox" name="choices[]" value="{{ $index }}" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500 rounded transition">
                                        <span class="ml-3.5 text-gray-700 font-medium group-hover:text-gray-900 transition">{!! $option !!}</span>
                                    </label>
                                @endforeach
                            </div>

                        <!-- DROPDOWN -->
                        @elseif($question->question_type === 'dropdown')
                            @if(!empty($parsed['subjects']))
                                <div class="space-y-4">
                                    @foreach($parsed['subjects'] as $sIndex => $subOptions)
                                        <div class="max-w-md">
                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5 font-sans">Dropdown #{{ $sIndex + 1 }}</label>
                                            <select class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-4 text-gray-700 font-medium bg-white">
                                                <option value="" disabled selected>Choose answer...</option>
                                                @foreach($subOptions as $option)
                                                    <option>{!! strip_tags($option) !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="max-w-md">
                                    <select class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-4 text-gray-700 font-medium bg-white">
                                        <option value="" disabled selected>Choose answer...</option>
                                        @foreach($parsed['options'] as $option)
                                            <option>{!! strip_tags($option) !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        <!-- TRUE/FALSE -->
                        @elseif($question->question_type === 'truefalse')
                            @if(!empty($parsed['subjects']))
                                <div class="space-y-4">
                                    @foreach($parsed['subjects'] as $sIndex => $subject)
                                        <div class="flex flex-col md:flex-row md:items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50/50 gap-4">
                                            <div class="text-gray-700 font-medium">{!! $subject !!}</div>
                                            <div class="flex space-x-2">
                                                <label class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-white cursor-pointer select-none transition">
                                                    <input type="radio" name="matrix_{{ $sIndex }}" value="yes" class="w-4 h-4 text-teal-600 focus:ring-teal-500">
                                                    <span class="ml-2 text-sm font-semibold text-gray-700">Yes</span>
                                                </label>
                                                <label class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-white cursor-pointer select-none transition">
                                                    <input type="radio" name="matrix_{{ $sIndex }}" value="no" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                                    <span class="ml-2 text-sm font-semibold text-gray-700">No</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <label class="flex flex-col items-center justify-center p-6 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/20 cursor-pointer transition duration-150 group">
                                        <input type="radio" name="tf" value="true" class="w-5 h-5 text-teal-600 border-gray-300 focus:ring-teal-500">
                                        <span class="mt-2 text-lg font-bold text-gray-700 group-hover:text-gray-900">True</span>
                                    </label>
                                    <label class="flex flex-col items-center justify-center p-6 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/20 cursor-pointer transition duration-150 group">
                                        <input type="radio" name="tf" value="false" class="w-5 h-5 text-teal-600 border-gray-300 focus:ring-teal-500">
                                        <span class="mt-2 text-lg font-bold text-gray-700 group-hover:text-gray-900">False</span>
                                    </label>
                                </div>
                            @endif

                        <!-- DRAG AND DROP — Fill in the Blank -->
                        @elseif($question->question_type === 'drag_and_drop')
                            <div class="space-y-5">
                                <p class="text-xs text-gray-500 italic">
                                    Drag tiles into the
                                    <span class="inline-flex items-center px-2 py-0.5 rounded border border-dashed border-amber-400 bg-amber-50 text-amber-700 font-bold text-xs mx-0.5">__ blank slots</span>
                                    in the text above. Click a placed tile to return it to the pool.
                                </p>

                                <!-- Answer pool + cancel zone -->
                                <div class="p-5 border-2 border-dashed border-amber-200 rounded-2xl bg-amber-50/20 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <p class="text-xs font-bold text-amber-500/80 uppercase tracking-widest">Answer Tiles</p>
                                            <button type="button" onclick="resetDndTiles()" class="px-2.5 py-1 bg-amber-200/60 hover:bg-amber-200 text-amber-900 font-bold text-xxs rounded-lg transition-all flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 11H18.21"/></svg>
                                                Reset Tiles
                                            </button>
                                        </div>
                                        <div id="dnd-cancel"
                                             style="display:none;"
                                             class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-dashed border-red-300 bg-red-50 text-red-500 font-bold text-xs select-none transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Drop here to cancel
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5" id="dnd-pool">
                                        @foreach($parsed['options'] as $option)
                                            <div class="dnd-tile bg-white hover:bg-amber-50 border-2 border-amber-300 text-amber-900 font-bold px-5 py-2.5 rounded-xl text-sm cursor-grab active:cursor-grabbing shadow-sm select-none transition-all hover:shadow-md hover:border-amber-400"
                                                 draggable="true">
                                                {!! $option !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submission Controls -->
                <div class="flex items-center justify-between bg-white border border-gray-150 p-6 rounded-2xl shadow-sm">
                    <a href="{{ route('admin.questions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                        Skip / Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow transition">
                        Save Correct Answer
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- JS Logic for capturing interactions & building correct answer string -->
    <script>
        let globalDragging = null;

        function resetDndTiles() {
            const textEl = document.getElementById('dnd-question-text');
            const pool = document.getElementById('dnd-pool');
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
                        const cancelZone = document.getElementById('dnd-cancel');
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
            updateAnswerContainer();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const questionType = "{{ $question->question_type }}";

            // Initialize correct answer based on type
            if (questionType === 'drag_and_drop') {
                initFillBlankDnD(
                    document.getElementById('dnd-question-text'),
                    document.getElementById('dnd-pool')
                );
            }

            // Register global event listeners on inputs & dropdowns
            document.addEventListener('change', (e) => {
                if (e.target.matches('input') || e.target.matches('select')) {
                    updateAnswerContainer();
                }
            });

            // Populate initial values if correct_answer_string already exists
            populateInitialAnswer(questionType);
        });

        function updateAnswerContainer() {
            const questionType = "{{ $question->question_type }}";
            const answerInput = document.getElementById('correct_answer_string');
            const answerDisplay = document.getElementById('answer-display-text');
            let answer = "";

            if (questionType === 'singleselect') {
                const checked = document.querySelector('input[name="choice"]:checked');
                if (checked) {
                    const label = checked.closest('label');
                    const textSpan = label.querySelector('span');
                    answer = textSpan.textContent.trim();
                }
            } else if (questionType === 'multiselect') {
                const checkedBoxes = Array.from(document.querySelectorAll('input[name="choices[]"]:checked'));
                const indices = checkedBoxes.map(cb => parseInt(cb.value) + 1).sort((a, b) => a - b);
                answer = indices.join(', ');
            } else if (questionType === 'truefalse') {
                const matrixRows = document.querySelectorAll('[name^="matrix_"]');
                if (matrixRows.length > 0) {
                    const rowNames = Array.from(new Set(Array.from(matrixRows).map(r => r.name)));
                    const answers = [];
                    rowNames.forEach(name => {
                        const checked = document.querySelector(`input[name="${name}"]:checked`);
                        answers.push(checked ? (checked.value === 'yes' ? 'Yes' : 'No') : '?');
                    });
                    answer = answers.join(', ');
                } else {
                    const checked = document.querySelector('input[name="tf"]:checked');
                    if (checked) {
                        answer = checked.value === 'true' ? 'True' : 'False';
                    }
                }
            } else if (questionType === 'dropdown') {
                const selects = document.querySelectorAll('select');
                const answers = [];
                selects.forEach(select => {
                    if (select.selectedIndex > 0) {
                        answers.push(select.options[select.selectedIndex].text.trim());
                    } else {
                        answers.push('?');
                    }
                });
                answer = answers.join(', ');
            } else if (questionType === 'drag_and_drop') {
                const slots = document.querySelectorAll('.dnd-slot');
                const answers = [];
                slots.forEach(slot => {
                    const tile = slot.querySelector('.dnd-tile');
                    if (tile) {
                        answers.push(tile.textContent.trim());
                    } else {
                        answers.push('?');
                    }
                });
                answer = answers.join(', ');
            }

            answerInput.value = answer;
            answerDisplay.textContent = answer || "No selection made yet...";
        }

        function populateInitialAnswer(questionType) {
            const initialVal = "{{ $question->correct_answer_string }}";
            if (!initialVal) return;

            if (questionType === 'singleselect') {
                const labels = document.querySelectorAll('label');
                labels.forEach(label => {
                    const span = label.querySelector('span');
                    if (span && span.textContent.trim() === initialVal) {
                        const input = label.querySelector('input');
                        if (input) {
                            input.checked = true;
                            updateAnswerContainer();
                        }
                    }
                });
            } else if (questionType === 'multiselect') {
                const indices = initialVal.split(',').map(idx => parseInt(idx.trim()) - 1);
                indices.forEach(idx => {
                    const cb = document.querySelector(`input[name="choices[]"][value="${idx}"]`);
                    if (cb) cb.checked = true;
                });
                updateAnswerContainer();
            } else if (questionType === 'truefalse') {
                const matrixRows = document.querySelectorAll('[name^="matrix_"]');
                if (matrixRows.length > 0) {
                    const answers = initialVal.split(',').map(s => s.trim().toLowerCase());
                    answers.forEach((ans, i) => {
                        const radio = document.querySelector(`input[name="matrix_${i}"][value="${ans === 'yes' ? 'yes' : 'no'}"]`);
                        if (radio) radio.checked = true;
                    });
                } else {
                    const isTrue = initialVal.toLowerCase() === 'true';
                    const radio = document.querySelector(`input[name="tf"][value="${isTrue ? 'true' : 'false'}"]`);
                    if (radio) radio.checked = true;
                }
                updateAnswerContainer();
            } else if (questionType === 'dropdown') {
                const answers = initialVal.split(',').map(s => s.trim());
                const selects = document.querySelectorAll('select');
                selects.forEach((select, selectIdx) => {
                    const expectedVal = answers[selectIdx];
                    if (expectedVal) {
                        Array.from(select.options).forEach((opt, optIdx) => {
                            if (opt.text.trim() === expectedVal) {
                                select.selectedIndex = optIdx;
                            }
                        });
                    }
                });
                updateAnswerContainer();
            }
        }

        // Fill-in-Blank Drag & Drop setup
        function initFillBlankDnD(textEl, pool) {
            if (!textEl || !pool) return;

            const cancelZone = document.getElementById('dnd-cancel');
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
                        globalDragging.style.cursor = '';
                        globalDragging.onclick = null;
                        setupTile(globalDragging);
                        pool.appendChild(globalDragging);
                    }
                    globalDragging = null;
                    cancelZone.style.display = 'none';
                    updateAnswerContainer();
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
                        const hasTile = !!slot.querySelector('.dnd-tile');
                        if (!hasTile) resetSlotStyle(slot);
                        else {
                            slot.style.borderColor = '#818cf8';
                            slot.style.background  = '#eef2ff';
                        }
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
                    globalDragging.title         = 'Click to return to pool';
                    globalDragging.onclick       = () => {
                        sendToPool(globalDragging, slot, true);
                        updateAnswerContainer();
                    };

                    slot.innerHTML      = '';
                    slot.style.minWidth = 'auto';
                    slot.style.borderStyle = 'solid';
                    slot.style.borderColor = '#818cf8';
                    slot.style.background  = '#eef2ff';
                    slot.style.padding     = '0 4px';
                    slot.style.height      = 'auto';
                    slot.style.minHeight   = '26px';
                    slot.appendChild(globalDragging);
                    globalDragging = null;

                    updateAnswerContainer();
                });
            });

            function sendToPool(tile, slot, clearSlot) {
                tile.draggable = true;
                tile.style.cursor = '';
                tile.style.opacity = '1';
                tile.title = '';
                tile.onclick = null;
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

            // Populate DND tiles if initialVal matches any options
            const initialVal = "{{ $question->correct_answer_string }}";
            if (initialVal) {
                const answers = initialVal.split(',').map(s => s.trim());
                textEl.querySelectorAll('.dnd-slot').forEach((slot, slotIdx) => {
                    const expectedVal = answers[slotIdx];
                    if (expectedVal) {
                        const matchingTile = Array.from(pool.querySelectorAll('.dnd-tile')).find(
                            t => t.textContent.trim() === expectedVal
                        );
                        if (matchingTile) {
                            matchingTile.style.opacity = '1';
                            matchingTile.style.cursor  = 'pointer';
                            matchingTile.draggable     = false;
                            matchingTile.title         = 'Click to return to pool';
                            matchingTile.onclick       = () => {
                                sendToPool(matchingTile, slot, true);
                                updateAnswerContainer();
                            };

                            slot.innerHTML      = '';
                            slot.style.minWidth = 'auto';
                            slot.style.borderStyle = 'solid';
                            slot.style.borderColor = '#818cf8';
                            slot.style.background  = '#eef2ff';
                            slot.style.padding     = '0 4px';
                            slot.style.height      = 'auto';
                            slot.style.minHeight   = '26px';
                            slot.appendChild(matchingTile);
                        }
                    }
                });
                updateAnswerContainer();
            }
        }
    </script>
</x-app-layout>
