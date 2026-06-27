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
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $color }}">
                                        {{ $formattedType }}
                                    </span>
                                </div>
                            </div>

                            <!-- Question Content -->
                            <div class="p-8 space-y-6">
                                <!-- Text -->
                                <div class="text-lg font-medium text-gray-800 leading-relaxed">
                                    {!! $parsed['text'] !!}
                                </div>

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
                                                <label class="flex items-center p-3.5 border border-gray-200 hover:border-blue-400 rounded-xl hover:bg-blue-50/10 cursor-pointer transition">
                                                    <input type="radio" name="choice_{{ $index }}" value="{{ $oIdx }}" class="w-4 h-4 text-blue-600 border-gray-300">
                                                    <span class="ml-3 text-gray-700 font-medium">{!! $option !!}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    <!-- Multiselect choices -->
                                    @elseif($qModel->question_type === 'multiselect')
                                        <div class="space-y-2.5">
                                            @foreach($parsed['options'] as $oIdx => $option)
                                                <label class="flex items-center p-3.5 border border-gray-200 hover:border-purple-400 rounded-xl hover:bg-purple-50/10 cursor-pointer transition">
                                                    <input type="checkbox" name="choices_{{ $index }}[]" value="{{ $oIdx }}" class="w-4 h-4 text-purple-600 border-gray-300 rounded">
                                                    <span class="ml-3 text-gray-700 font-medium">{!! $option !!}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    <!-- Dropdown choices -->
                                    @elseif($qModel->question_type === 'dropdown')
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

                                    <!-- Drag and Drop choices -->
                                    @elseif($qModel->question_type === 'drag_and_drop')
                                        @php
                                            $isMatching = count($parsed['options']) > 0 && is_array($parsed['options'][0]) && isset($parsed['options'][0]['left']);
                                        @endphp
                                        
                                        @if($isMatching)
                                            <div class="space-y-3">
                                                @foreach($parsed['options'] as $pIdx => $pair)
                                                    <div class="flex items-center gap-3 bg-gray-50/50 p-3.5 border border-gray-150 rounded-xl">
                                                        <div class="w-1/3 font-bold text-xs md:text-sm text-gray-800">{!! $pair['left'] !!}</div>
                                                        <div class="flex-grow h-12 bg-white border border-dashed border-gray-300 rounded-lg flex items-center justify-center text-xs text-gray-400">
                                                            [ Drop Slot ]
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                @foreach($parsed['options'] as $option)
                                                    <div class="flex items-center bg-gray-50 border border-gray-200 p-3 rounded-lg text-sm text-gray-700">
                                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                                        </svg>
                                                        {!! $option !!}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Bottom Answer Card (Admin togglable) -->
                            <div class="border-t border-gray-100 bg-gray-800 text-white overflow-hidden">
                                <button type="button" onclick="toggleAnswerCard('answer-box-{{ $index }}')" class="w-full px-6 py-3.5 flex items-center justify-between text-left text-xs font-bold uppercase tracking-wider text-amber-400 focus:outline-none hover:bg-gray-750 transition">
                                    <span>Correct Answer Key</span>
                                    <svg class="answer-chevron-icon w-4 h-4 transform rotate-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div id="answer-box-{{ $index }}" class="answer-box-content hidden px-6 pb-6 pt-2 border-t border-gray-700 bg-gray-850/50">
                                    <div class="text-base font-bold text-white bg-gray-900/60 p-3 rounded-lg border border-gray-700/30 inline-block">
                                        {{ $qModel->correct_answer_string ?: 'N/A' }}
                                    </div>
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
    </script>
</x-app-layout>
