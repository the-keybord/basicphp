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
                    <div class="text-xl font-medium text-gray-800 leading-relaxed">
                        {!! $parsed['text'] !!}
                    </div>
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
                        <div class="max-w-md">
                            <select class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-4 text-gray-700 font-medium bg-white">
                                <option value="" disabled selected>Choose your answer...</option>
                                @foreach($parsed['options'] as $option)
                                    <option>{!! strip_tags($option) !!}</option>
                                @endforeach
                            </select>
                        </div>

                    <!-- TRUE/FALSE -->
                    @elseif($question->question_type === 'truefalse')
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

                    <!-- DRAG AND DROP (MATCHING PAIRS / REORDERING) -->
                    @elseif($question->question_type === 'drag_and_drop')
                        @php
                            $isMatching = count($parsed['options']) > 0 && is_array($parsed['options'][0]) && isset($parsed['options'][0]['left']);
                        @endphp

                        @if($isMatching)
                            <!-- Drag & Drop Matching Layout -->
                            <div class="space-y-6">
                                <p class="text-xs text-gray-500 italic">Drag the definitions from the pool below and drop them next to the correct terms.</p>
                                
                                <div class="space-y-4">
                                    @foreach($parsed['options'] as $index => $pair)
                                        <div class="flex items-center gap-4 bg-gray-50 p-4 border border-gray-100 rounded-xl">
                                            <!-- Left item -->
                                            <div class="w-1/3 font-bold text-gray-800 text-sm md:text-base border-r border-gray-200 pr-2">
                                                {!! $pair['left'] !!}
                                            </div>
                                            <!-- Drop target zone -->
                                            <div class="flex-grow dropzone h-14 bg-white border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center text-xs font-semibold text-gray-400 tracking-wider hover:border-amber-400 transition" data-match-id="{{ $index }}">
                                                Drop matching item here
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Draggable items pool -->
                                <div class="mt-6 p-5 border border-dashed border-gray-200 rounded-xl bg-gray-50/30">
                                    <h4 class="font-bold text-xs text-gray-500 uppercase mb-3">Options Pool</h4>
                                    <div class="flex flex-wrap gap-2.5" id="draggable-pool">
                                        @foreach(collect($parsed['options'])->shuffle() as $pair)
                                            <div class="drag-item bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 font-semibold px-4 py-2.5 rounded-lg text-xs md:text-sm cursor-grab active:cursor-grabbing shadow-sm select-none transition" draggable="true" data-text="{!! strip_tags($pair['right']) !!}">
                                                {!! $pair['right'] !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Drag & Drop Sortable Ordering Layout -->
                            <div class="space-y-4">
                                <p class="text-xs text-gray-500 italic">Drag and reorder the options vertically to set the sequence.</p>
                                <div class="space-y-2.5" id="sortable-list">
                                    @foreach($parsed['options'] as $index => $option)
                                        <div class="sort-item flex items-center bg-gray-50 hover:bg-gray-100 border border-gray-200 p-4 rounded-xl cursor-move shadow-sm select-none transition group" draggable="true">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            </svg>
                                            <span class="text-gray-700 font-medium">{!! $option !!}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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

    <!-- Drag & Drop matching logic script -->
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

        document.addEventListener('DOMContentLoaded', () => {
            // Drag and drop matching handler
            const dragItems = document.querySelectorAll('.drag-item');
            const dropzones = document.querySelectorAll('.dropzone');
            let activeDragElement = null;

            dragItems.forEach(item => {
                item.addEventListener('dragstart', (e) => {
                    activeDragElement = item;
                    e.dataTransfer.setData('text/plain', item.getAttribute('data-text'));
                    item.classList.add('opacity-50');
                });

                item.addEventListener('dragend', () => {
                    item.classList.remove('opacity-50');
                    activeDragElement = null;
                });
            });

            dropzones.forEach(zone => {
                zone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    zone.classList.add('border-amber-400', 'bg-amber-50/10');
                });

                zone.addEventListener('dragleave', () => {
                    zone.classList.remove('border-amber-400', 'bg-amber-50/10');
                });

                zone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    zone.classList.remove('border-amber-400', 'bg-amber-50/10');

                    if (activeDragElement) {
                        // Clear text and append element
                        zone.innerHTML = '';
                        const clone = activeDragElement.cloneNode(true);
                        clone.classList.remove('shadow-sm', 'cursor-grab', 'px-4', 'py-2.5');
                        clone.classList.add('w-full', 'h-full', 'flex', 'items-center', 'justify-center', 'text-center', 'font-bold');
                        clone.setAttribute('draggable', 'false');
                        zone.appendChild(clone);

                        // Remove from original pool
                        activeDragElement.remove();
                    }
                });
            });

            // Sortable ordering handler
            const sortList = document.getElementById('sortable-list');
            if (sortList) {
                let dragItem = null;

                sortList.addEventListener('dragstart', (e) => {
                    dragItem = e.target.closest('.sort-item');
                    if (dragItem) {
                        setTimeout(() => dragItem.classList.add('opacity-40'), 0);
                    }
                });

                sortList.addEventListener('dragend', (e) => {
                    if (dragItem) {
                        dragItem.classList.remove('opacity-40');
                        dragItem = null;
                    }
                });

                sortList.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(sortList, e.clientY);
                    if (afterElement == null) {
                        sortList.appendChild(dragItem);
                    } else {
                        sortList.insertBefore(dragItem, afterElement);
                    }
                });
            }

            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.sort-item:not(.opacity-40)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }
        });
    </script>
</x-app-layout>
