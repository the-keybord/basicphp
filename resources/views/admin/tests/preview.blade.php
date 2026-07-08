<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.tests.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Preview: {{ $test->name }}
                </h2>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="px-3.5 py-1.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-bold uppercase tracking-wider">
                    Total: {{ $renderedQuestions->count() }} Questions
                </span>
            </div>
        </div>
    </x-slot>

    <style>
        .whitespace-pre-wrap,
        .whitespace-pre-wrap p,
        .whitespace-pre-wrap pre,
        .whitespace-pre-wrap code {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.25 !important;
        }
        .dnd-tile.dnd-placed {
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            line-height: 1.25 !important;
            border-radius: 6px !important;
            border-width: 1px !important;
            margin: 0 2px !important;
            display: inline-block !important;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Sticky Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-150 p-5 shadow-sm sticky top-6 space-y-4">
                        <div>
                            <h3 class="font-bold text-xs text-gray-400 uppercase tracking-widest">Test Navigation</h3>
                            <p class="text-xs text-gray-500 mt-1">Click a number below to jump to that question card.</p>
                        </div>

                        <div class="grid grid-cols-5 gap-2" id="nav-badge-grid">
                            @foreach($renderedQuestions as $index => $item)
                                <a href="#question-card-{{ $index + 1 }}" class="h-10 w-10 flex items-center justify-center border border-gray-200 hover:border-blue-500 hover:bg-blue-50 rounded-lg text-sm font-bold text-gray-700 hover:text-blue-800 transition">
                                    {{ $index + 1 }}
                                </a>
                            @endforeach
                        </div>

                        <div class="pt-4 border-t border-gray-150 flex flex-col gap-2">
                            <button type="button" onclick="toggleAllAnswers(true)" class="w-full text-center bg-gray-800 hover:bg-gray-750 text-white text-xs font-semibold py-2 px-3 rounded-lg shadow-sm transition">
                                Reveal All Answer Keys
                            </button>
                            <button type="button" onclick="toggleAllAnswers(false)" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 px-3 rounded-lg border border-gray-300 transition">
                                Hide All Answer Keys
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Questions Stack Area -->
                <div class="lg:col-span-3 space-y-8">
                    @foreach($renderedQuestions as $index => $item)
                        @php
                            $qModel = $item['model'];
                            $parsed = $item['parsed'];
                            $badgeColors = [
                                'multiselect' => 'bg-purple-50 text-purple-700 border-purple-100',
                                'singleselect' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                'dropdown' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'drag_and_drop' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'truefalse' => 'bg-teal-50 text-teal-700 border-teal-100',
                            ];
                            $color = $badgeColors[$qModel->question_type] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                            $formattedType = str_replace('_', ' ', $qModel->question_type);
                        @endphp
                        
                        <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden scroll-mt-6" id="question-card-{{ $index + 1 }}">
                            
                            <!-- Header Info -->
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center space-x-3">
                                    <span class="h-8 w-8 bg-blue-600 text-white font-black text-sm flex items-center justify-center rounded-lg shadow-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="text-xs">
                                        <span class="text-gray-400 font-bold uppercase tracking-widest">Subcategory</span>
                                        <span class="font-bold text-gray-700 ml-1">{{ $qModel->primarySubcategory->name }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="text-xxs text-gray-400 font-semibold px-2 py-0.5 bg-gray-100 rounded border border-gray-200 uppercase">
                                        ID: {{ $qModel->id }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $color }}">
                                        {{ $formattedType }}
                                    </span>
                                    <a href="{{ route('admin.questions.preview', $qModel) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition" title="Preview Question">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Preview
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $qModel) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-lg text-xs font-semibold transition" title="Edit Question">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </div>

                            <!-- Question Content -->
                            <div class="p-8 space-y-6">
                                @php
                                    $questionText = $parsed['text'];
                                    $hasInlineDropdowns = false;
                                    if ($qModel->question_type === 'dropdown' && !empty($parsed['subjects']) && str_contains($questionText, '__')) {
                                        $hasInlineDropdowns = true;
                                        $sIdx = 0;
                                        $questionText = preg_replace_callback('/__/', function($match) use ($qModel, $parsed, &$sIdx) {
                                            $subOptions = $parsed['subjects'][$sIdx] ?? [];
                                            $sIdx++;
                                            
                                            $optionsHtml = '<option value="" disabled selected>Choose...</option>';
                                            foreach ($subOptions as $option) {
                                                $optionsHtml .= '<option value="' . htmlspecialchars(strip_tags($option)) . '">' . strip_tags($option) . '</option>';
                                            }
                                            
                                            return '<select class="inline-block border-gray-300 rounded-lg py-1.5 px-3 mx-1 text-sm text-gray-700 font-medium bg-white focus:ring-blue-500 shadow-sm align-middle max-w-[200px]">' . $optionsHtml . '</select>';
                                        }, $questionText);
                                    }
                                @endphp

                                <!-- Text -->
                                @if($hasInlineDropdowns)
                                    <div class="text-lg font-medium text-gray-800 leading-relaxed whitespace-pre-wrap">{!! $questionText !!}</div>
                                @else
                                    <pre class="text-lg font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;"@if($qModel->question_type === 'drag_and_drop') id="dnd-text-{{ $index }}" @endif>{!! $parsed['text'] !!}</pre>
                                @endif

                                <!-- Image Diagram -->
                                @if(!empty($parsed['image']))
                                    <div>
                                        @php
                                            $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm" alt="Question Diagram">
                                    </div>
                                @endif

                                <!-- Choices block -->
                                <div class="pt-4 border-t border-gray-50">
                                    <!-- Singleselect choices -->
                                    @if($qModel->question_type === 'singleselect')
                                        <div class="space-y-2.5">
                                            @foreach($parsed['options'] as $oIdx => $option)
                                                <label class="flex items-start p-3.5 border border-gray-200 hover:border-blue-400 rounded-xl hover:bg-blue-50/10 cursor-pointer transition">
                                                    <input type="radio" name="choice_{{ $index }}" value="{{ $oIdx }}" class="w-4 h-4 text-blue-600 border-gray-300 mt-1">
                                                    <span class="ml-3 text-gray-700 font-medium whitespace-pre-wrap leading-snug [&_p]:m-0 [&_pre]:m-0 [&_code]:m-0 [&_ul]:m-0 [&_ol]:m-0">{!! $option !!}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    <!-- Multiselect choices -->
                                    @elseif($qModel->question_type === 'multiselect')
                                        <div class="space-y-2.5">
                                            @foreach($parsed['options'] as $oIdx => $option)
                                                <label class="flex items-start p-3.5 border border-gray-200 hover:border-purple-400 rounded-xl hover:bg-purple-50/10 cursor-pointer transition">
                                                    <input type="checkbox" name="choices_{{ $index }}[]" value="{{ $oIdx }}" class="w-4 h-4 text-purple-600 border-gray-300 rounded mt-1">
                                                    <span class="ml-3 text-gray-700 font-medium whitespace-pre-wrap leading-snug [&_p]:m-0 [&_pre]:m-0 [&_code]:m-0 [&_ul]:m-0 [&_ol]:m-0">{!! $option !!}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    <!-- Dropdown choices -->
                                    @elseif($qModel->question_type === 'dropdown')
                                        @if(!$hasInlineDropdowns)
                                            @if(!empty($parsed['subjects']))
                                                <div class="space-y-4">
                                                    @foreach($parsed['subjects'] as $sIdx => $subOptions)
                                                        <div class="max-w-md">
                                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Dropdown #{{ $sIdx + 1 }}</label>
                                                            <select class="block w-full border-gray-300 rounded-xl p-3 text-sm text-gray-700 font-medium bg-white">
                                                                <option disabled selected>Choose answer...</option>
                                                                @foreach($subOptions as $opt)
                                                                    <option>{!! strip_tags($opt) !!}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="max-w-md">
                                                    <select class="block w-full border-gray-300 rounded-xl p-3 text-sm text-gray-700 font-medium bg-white">
                                                        <option disabled selected>Choose answer...</option>
                                                        @foreach($parsed['options'] as $opt)
                                                            <option>{!! strip_tags($opt) !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @endif

                                    <!-- True/False choices -->
                                    @elseif($qModel->question_type === 'truefalse')
                                        @if(!empty($parsed['subjects']))
                                            <div class="space-y-3">
                                                @foreach($parsed['subjects'] as $sIdx => $subject)
                                                    <div class="flex items-center justify-between p-3.5 border border-gray-200 rounded-xl bg-gray-50/50 gap-4">
                                                        <div class="text-sm font-semibold text-gray-700">{!! $subject !!}</div>
                                                        <div class="flex space-x-2">
                                                            <label class="flex items-center px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-white text-xs font-bold text-gray-600 cursor-pointer select-none transition">
                                                                <input type="radio" name="tf_{{ $index }}_{{ $sIdx }}" value="yes" class="w-3.5 h-3.5 text-teal-600">
                                                                <span class="ml-1.5">Yes</span>
                                                            </label>
                                                            <label class="flex items-center px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-white text-xs font-bold text-gray-600 cursor-pointer select-none transition">
                                                                <input type="radio" name="tf_{{ $index }}_{{ $sIdx }}" value="no" class="w-3.5 h-3.5 text-red-600">
                                                                <span class="ml-1.5">No</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="grid grid-cols-2 gap-3">
                                                <label class="flex flex-col items-center justify-center p-4 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/10 cursor-pointer transition">
                                                    <input type="radio" name="tf_{{ $index }}" value="true" class="w-4 h-4 text-teal-600">
                                                    <span class="mt-1 text-sm font-bold text-gray-700">True</span>
                                                </label>
                                                <label class="flex flex-col items-center justify-center p-4 border border-gray-200 hover:border-teal-400 rounded-xl hover:bg-teal-50/10 cursor-pointer transition">
                                                    <input type="radio" name="tf_{{ $index }}" value="false" class="w-4 h-4 text-teal-600">
                                                    <span class="mt-1 text-sm font-bold text-gray-700">False</span>
                                                </label>
                                            </div>
                                        @endif

                                    <!-- Drag and Drop — Fill in the Blank -->
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
                                                        <button type="button" onclick="resetDndTiles({{ $index }})" class="px-2.5 py-1 bg-amber-200/60 hover:bg-amber-200 text-amber-900 font-bold text-xxs rounded-lg transition-all flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 11H18.21"/></svg>
                                                            Reset
                                                        </button>
                                                    </div>
                                                    <div id="dnd-cancel-{{ $index }}"
                                                         style="display:none;"
                                                         class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-dashed border-red-300 bg-red-50 text-red-500 font-bold text-xs select-none transition-all">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Cancel
                                                    </div>
                                                </div>
                                                <div class="flex flex-wrap gap-2" id="dnd-pool-{{ $index }}">
                                                    @foreach($parsed['options'] as $option)
                                                        <div class="dnd-tile bg-white hover:bg-amber-50 border-2 border-amber-300 text-amber-900 font-bold px-4 py-2 rounded-lg text-sm cursor-grab active:cursor-grabbing shadow-sm select-none transition-all hover:shadow-md"
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

                            <!-- Bottom Answer Card (Admin togglable) -->
                            <div class="border-t border-gray-100 bg-gray-800 text-white overflow-hidden">
                                <button type="button" onclick="toggleAnswerCard('answer-box-{{ $index }}')" class="w-full px-6 py-3.5 flex items-center justify-between text-left text-xs font-bold uppercase tracking-wider text-amber-400 focus:outline-none hover:bg-gray-750 transition">
                                    <span>Correct Answer</span>
                                    <svg class="w-5 h-5 text-gray-400 answer-chevron-icon transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div id="answer-box-{{ $index }}" class="hidden px-6 pb-6 pt-2 border-t border-gray-700 bg-gray-850/50 text-gray-300 text-sm">
                                    {{ $qModel->correct_answer_string ?: 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <!-- JS for full test previews -->
    <script>
        function toggleAnswerCard(id) {
            const el = document.getElementById(id);
            const button = el.previousElementSibling;
            const icon = button.querySelector('.answer-chevron-icon');
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                el.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        function toggleAllAnswers(reveal) {
            const boxes = document.querySelectorAll('.answer-box-content');
            boxes.forEach(box => {
                const icon = box.previousElementSibling.querySelector('.answer-chevron-icon');
                if (reveal) {
                    box.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    box.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        }

        let globalDragging = null;

        function resetDndTiles(idx) {
            const textEl = document.getElementById('dnd-text-' + idx);
            const pool = document.getElementById('dnd-pool-' + idx);
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
                        const cancelZone = document.getElementById('dnd-cancel-' + idx);
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
        }

        // Initialise fill-in-blank DnD for every drag_and_drop question card
        function initFillBlankDnD(textEl, pool, cancelZone, idx) {
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
                    if (parentSlot) sendToPool(globalDragging, parentSlot, true);
                    else {
                        globalDragging.style.opacity = '1';
                        globalDragging.draggable = true;
                        globalDragging.onclick = null;
                        setupTile(globalDragging);
                        pool.appendChild(globalDragging);
                    }
                    globalDragging = null;
                    cancelZone.style.display = 'none';
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
                    globalDragging.onclick       = () => sendToPool(globalDragging, slot, true);
                    globalDragging.classList.add('dnd-placed');

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
                });
            });

            function sendToPool(tile, slot, clearSlot) {
                tile.draggable    = true;
                tile.style.cursor = '';
                tile.style.opacity = '1';
                tile.title        = '';
                tile.onclick      = null;
                tile.classList.remove('dnd-placed');
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
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Each DnD question on the page gets its own independent instance
            document.querySelectorAll('[id^="dnd-text-"]').forEach(textEl => {
                const idx        = textEl.id.replace('dnd-text-', '');
                const pool       = document.getElementById('dnd-pool-' + idx);
                const cancelZone = document.getElementById('dnd-cancel-' + idx);
                initFillBlankDnD(textEl, pool, cancelZone, idx);
            });
        });
    </script>
</x-app-layout>
