<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Alert Banner -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Title & Add Question Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-lg text-gray-800 leading-tight border-b border-gray-100 pb-3">
                        Question Bank
                    </h2>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.questions.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Question
                        </a>
                        
                        @if($questions->isNotEmpty())
                            <a href="{{ route('admin.questions.preview', $questions->first()->id, false) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Slideshow Review
                            </a>
                        @endif

                        <a href="{{ route('admin.questions.siblings') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Sister Questions
                        </a>
                    </div>
                </div>

                <!-- Search & Filters Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-450 uppercase tracking-widest block border-b border-gray-100 pb-2">Filter Search</span>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Query</label>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" id="search-input" placeholder="Search questions..." class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs transition bg-white">
                        </div>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</span>
                        <div class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100" id="search-results-count">
                            Showing {{ count($questions) }} questions
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Table area) -->
            <main class="flex-grow w-full min-w-0 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900">
                @if($questions->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No questions found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new XML-based question.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.questions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">
                                Create first question
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="id">
                                        ID <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                    </th>
                                    <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="type">
                                        Type <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                    </th>
                                    <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="primary">
                                        Primary Subcategory <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                    </th>
                                    <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="secondary">
                                        Secondary Subcategory <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($questions as $question)
                                    @php
                                        $textMatch = [];
                                        preg_match('/<text>(.*?)<\/text>/s', $question->xml_content, $textMatch);
                                        $questionText = isset($textMatch[1]) ? strip_tags(html_entity_decode($textMatch[1])) : '';
                                    @endphp
                                    <!-- Row 1: Metadata -->
                                    <tr class="question-row hover:bg-gray-50/30 transition"
                                        data-id="{{ $question->id }}"
                                        data-type="{{ $question->question_type }}"
                                        data-primary="{{ optional($question->primarySubcategory)->name ?? '' }}"
                                        data-secondary="{{ optional($question->secondarySubcategory)->name ?? '' }}"
                                        data-answer="{{ $question->correct_answer_string }}"
                                        data-created="{{ $question->created_at->timestamp }}"
                                        data-text="{{ $questionText }}">
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs font-semibold text-gray-700">
                                            #{{ $question->id }}
                                        </td>
                                        <td class="px-4 py-2.5 whitespace-nowrap">
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
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $color }} capitalize">
                                                {{ $formattedType }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2.5 text-xs text-gray-600">
                                            <div class="max-w-[200px] truncate" title="{{ optional($question->primarySubcategory)->name ?? '' }}">
                                                @if($question->primarySubcategory)
                                                    <span class="text-[9px] text-gray-400 block font-medium uppercase tracking-wider truncate">{{ $question->primarySubcategory->category->name }}</span>
                                                    <span class="font-medium text-xs text-gray-900 truncate">{{ $question->primarySubcategory->name }}</span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2.5 text-xs text-gray-600">
                                            <div class="max-w-[200px] truncate" title="{{ optional($question->secondarySubcategory)->name ?? '' }}">
                                                @if($question->secondarySubcategory)
                                                    <span class="text-[9px] text-gray-400 block font-medium uppercase tracking-wider truncate">{{ $question->secondarySubcategory->category->name }}</span>
                                                    <span class="font-medium text-xs text-gray-900 truncate">{{ $question->secondarySubcategory->name }}</span>
                                                @else
                                                    <span class="text-gray-400 italic">None</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row 2: Text Preview & Actions -->
                                    <tr class="question-row-details border-b border-gray-150 bg-gray-50/20 hover:bg-gray-50/40 transition" data-id="{{ $question->id }}">
                                        <td colspan="4" class="px-4 py-2">
                                            <div class="flex items-center gap-4">
                                                <!-- Action Buttons -->
                                                <div class="flex items-center space-x-1.5 flex-shrink-0">
                                                    <!-- Preview -->
                                                    <a href="{{ route('admin.questions.preview', $question) }}" class="inline-flex items-center p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-100 transition" title="Preview Question">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                    <!-- Edit -->
                                                    <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg border border-transparent hover:border-amber-100 transition" title="Edit Question">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <!-- Delete -->
                                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center p-1.5 text-red-600 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-100 transition" title="Delete Question">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <!-- Stripped XML content text -->
                                                <div class="text-[10px] text-gray-400 font-normal truncate max-w-[800px]" title="{{ $questionText }}">
                                                    <span class="font-bold text-gray-505 mr-1">Preview:</span> "{{ $questionText }}"
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>
        </div>
    </div>


    <!-- Client-side Sorting & Filtering Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const tableBody = document.querySelector('table tbody');
            const rows = Array.from(tableBody.querySelectorAll('.question-row'));
            const resultsCount = document.getElementById('search-results-count');

            // 1. Search filter functionality
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase().trim();
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const id = row.getAttribute('data-id');
                        const detailsRow = document.querySelector(`.question-row-details[data-id="${id}"]`);
                        
                        const textContent = row.textContent.toLowerCase();
                        const detailsText = detailsRow ? detailsRow.textContent.toLowerCase() : '';
                        const questionText = (row.getAttribute('data-text') || '').toLowerCase();
                        const text = textContent + ' ' + detailsText + ' ' + questionText;

                        if (text.includes(query)) {
                            row.style.display = '';
                            if (detailsRow) detailsRow.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                            if (detailsRow) detailsRow.style.display = 'none';
                        }
                    });

                    resultsCount.textContent = `Showing ${visibleCount} of ${rows.length} questions`;
                });
            }

            // 2. Interactive Column sorting functionality
            let currentSortCol = null;
            let currentSortAsc = true;

            document.querySelectorAll('th.sortable').forEach(header => {
                header.addEventListener('click', () => {
                    const col = header.getAttribute('data-col');

                    // Toggle ascending/descending order
                    if (currentSortCol === col) {
                        currentSortAsc = !currentSortAsc;
                    } else {
                        currentSortCol = col;
                        currentSortAsc = true;
                    }

                    // Reset sort icons
                    document.querySelectorAll('th.sortable .sort-icon').forEach(icon => {
                        icon.textContent = '⇅';
                        icon.className = 'sort-icon ml-1 text-gray-400';
                    });

                    // Set active indicator
                    const activeIcon = header.querySelector('.sort-icon');
                    activeIcon.textContent = currentSortAsc ? '▲' : '▼';
                    activeIcon.className = 'sort-icon ml-1 text-blue-600 font-bold';

                    // Sort rows using the raw data attributes to ensure proper type conversions
                    const sortedRows = rows.sort((a, b) => {
                        let valA = a.getAttribute(`data-${col}`);
                        let valB = b.getAttribute(`data-${col}`);

                        // Handle numerical comparison
                        if (col === 'id' || col === 'created') {
                            const numA = parseInt(valA) || 0;
                            const numB = parseInt(valB) || 0;
                            return currentSortAsc ? (numA - numB) : (numB - numA);
                        }

                        // Handle string comparison (alphabetical)
                        valA = valA.toLowerCase();
                        valB = valB.toLowerCase();

                        if (valA < valB) return currentSortAsc ? -1 : 1;
                        if (valA > valB) return currentSortAsc ? 1 : -1;
                        return 0;
                    });

                    // Re-append sorted rows to the table DOM along with their details row
                    sortedRows.forEach(row => {
                        tableBody.appendChild(row);
                        const detailsRow = document.querySelector(`.question-row-details[data-id="${row.getAttribute('data-id')}"]`);
                        if (detailsRow) {
                            tableBody.appendChild(detailsRow);
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>