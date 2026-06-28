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
                    {{ __('Question Preview') }}
                </h2>
            </div>
            
            <div class="flex space-x-2">
                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold uppercase tracking-wider">
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
            
            <!-- Category & Metadata Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row justify-between gap-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Primary Mapping</h3>
                    <div class="mt-1 text-sm">
                        <span class="font-bold text-blue-600">{{ ucfirst($question->primarySubcategory->category->name) }}</span>
                        <span class="text-gray-400 mx-1.5">/</span>
                        <span class="font-semibold text-gray-700">{{ $question->primarySubcategory->name }}</span>
                    </div>
                </div>

                @if($question->secondarySubcategory)
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Secondary Mapping</h3>
                        <div class="mt-1 text-sm">
                            <span class="font-bold text-indigo-600">{{ ucfirst($question->secondarySubcategory->category->name) }}</span>
                            <span class="text-gray-400 mx-1.5">/</span>
                            <span class="font-semibold text-gray-700">{{ $question->secondarySubcategory->name }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Question & Rendering Preview Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-md overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-2">Student View Mockup</span>
                    
                    <!-- Question Text -->
                    <pre class="text-xl font-medium text-gray-800 leading-relaxed" style="font-family:inherit;white-space:pre-wrap;margin:0;padding:0;background:transparent;border:none;overflow:visible;"@if($question->question_type === 'drag_and_drop') id="dnd-question-text" @endif>{!! $parsed['text'] !!}</pre>

                    <!-- Question Image (if any) -->
                    @if(!empty($parsed['image']))
                        <div class="mt-4">
                            @php
                                $imageUrl = str_starts_with($parsed['image'], 'http') ? $parsed['image'] : asset($parsed['image']);
                            @endphp
                            <img src="{{ $imageUrl }}" class="rounded-xl border border-gray-150 max-w-full shadow-sm" alt="Question diagram">
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
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Dropdown #{{ $sIndex + 1 }}</label>
                                        <select class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-4 text-gray-700 font-medium bg-white">
                                            <option value="" disabled selected>Choose your answer...</option>
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
                                    <option value="" disabled selected>Choose your answer...</option>
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

                            <!-- Answer pool + cancel zone side by side -->
                            <div class="p-5 border-2 border-dashed border-amber-200 rounded-2xl bg-amber-50/20 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <p class="text-xs font-bold text-amber-500/80 uppercase tracking-widest">Answer Tiles</p>
                                        <button type="button" onclick="resetDndTiles()" class="px-2.5 py-1 bg-amber-200/60 hover:bg-amber-200 text-amber-900 font-bold text-xxs rounded-lg transition-all flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 11H18.21"/></svg>
                                            Reset Tiles
                                        </button>
                                    </div>
                                    <!-- Cancel drop zone: only visible while dragging -->
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

            <!-- Teacher/Admin Correct Answer Panel -->
            <div class="bg-gray-800 rounded-xl border border-gray-900 text-white overflow-hidden shadow-md">
                <button type="button" onclick="toggleAnswer()" class="w-full px-6 py-4 flex items-center justify-between font-semibold text-left focus:outline-none hover:bg-gray-750 transition">
                    <span class="flex items-center text-amber-400 font-bold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Teacher Answer Key
                    </span>
                    <svg id="answer-chevron" class="w-5 h-5 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="answer-content" class="hidden px-6 pb-6 pt-2 border-t border-gray-700/50 bg-gray-850/50 text-gray-300 text-sm space-y-4">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-1">Separated Answer String</span>
                        <div class="text-lg font-bold text-white bg-gray-900/60 p-3 rounded-lg border border-gray-700/30 inline-block">
                            {{ $question->correct_answer_string ?: 'No answer specified' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-1">Raw XML Store</span>
                        <pre class="bg-gray-900/80 p-4 rounded-lg font-mono text-xs text-gray-400 overflow-x-auto border border-gray-700/30 whitespace-pre-wrap">{{ $question->xml_content }}</pre>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Fill-in-Blank Drag & Drop + Answer Toggle -->
    <script>
        function toggleAnswer() {
            const content = document.getElementById('answer-content');
            const chevron = document.getElementById('answer-chevron');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

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
                    
                    // Re-register drag start/end to be safe
                    tile.addEventListener('dragstart', e => {
                        globalDragging = tile;
                        setTimeout(() => (tile.style.opacity = '0.4'), 0);
                        e.dataTransfer.effectAllowed = 'move';
                        const cancelZone = document.getElementById('dnd-cancel');
                        if (cancelZone) cancelZone.style.display = 'flex';
                    });
                    
                    pool.appendChild(tile);
                    slot.innerHTML = '&nbsp;?&nbsp;';
                    
                    // Reset slot styles
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

        document.addEventListener('DOMContentLoaded', () => {
            initFillBlankDnD(
                document.getElementById('dnd-question-text'),
                document.getElementById('dnd-pool')
            );
        });

        function initFillBlankDnD(textEl, pool) {
            if (!textEl || !pool) return;

            const cancelZone = document.getElementById('dnd-cancel');
            let slotIdx = 0;

            // Replace every __ in the rendered text with a styled drop zone
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
                    if (cancelZone) {
                        cancelZone.style.display = 'flex';
                    }
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

            // Cancel zone: drop a tile here to return it to the pool
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
                    // Find the slot that holds this tile (if any) and clear it
                    const parentSlot = globalDragging.closest('.dnd-slot');
                    if (parentSlot) sendToPool(globalDragging, parentSlot, true);
                    else {
                        globalDragging.style.opacity = '1';
                        globalDragging.draggable = true;
                        globalDragging.style.cursor = '';
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

                    // If the slot already has a tile, return it to pool first
                    const prev = slot.querySelector('.dnd-tile');
                    if (prev) sendToPool(prev, slot, false);

                    // Place the dragged tile inside the slot
                    globalDragging.style.opacity = '1';
                    globalDragging.style.cursor  = 'pointer';
                    globalDragging.draggable     = false;
                    globalDragging.title         = 'Click to return to pool';
                    globalDragging.onclick       = () => sendToPool(globalDragging, slot, true);

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
        }
    </script>
</x-app-layout>
