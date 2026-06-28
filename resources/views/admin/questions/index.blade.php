<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Question Bank') }}
            </h2>
            <a href="{{ route('admin.questions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Question
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Banner -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
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
                        <!-- Search and Metadata Control Bar -->
                        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                            <div class="relative w-full md:max-w-md">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input type="text" id="search-input" placeholder="Search by ID, type, subcategory, or correct answer..." class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm transition">
                            </div>
                            <div class="text-xs font-bold text-gray-450 uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-150" id="search-results-count">
                                Showing {{ count($questions) }} questions
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="id">
                                            ID <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="type">
                                            Type <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="primary">
                                            Primary Subcategory <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="secondary">
                                            Secondary Subcategory <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="answer">
                                            Correct Answer <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="sortable cursor-pointer hover:bg-gray-100 select-none px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider transition" data-col="created">
                                            Created <span class="sort-icon ml-1 text-gray-400">⇅</span>
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($questions as $question)
                                        <tr class="question-row hover:bg-gray-50/50 transition"
                                            data-id="{{ $question->id }}"
                                            data-type="{{ $question->question_type }}"
                                            data-primary="{{ optional($question->primarySubcategory)->name ?? '' }}"
                                            data-secondary="{{ optional($question->secondarySubcategory)->name ?? '' }}"
                                            data-answer="{{ $question->correct_answer_string }}"
                                            data-created="{{ $question->created_at->timestamp }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                                #{{ $question->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $color }} capitalize">
                                                    {{ $formattedType }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                @if($question->primarySubcategory)
                                                    <span class="text-xs text-gray-400 block font-medium uppercase tracking-wider">{{ $question->primarySubcategory->category->name }}</span>
                                                    <span class="font-medium text-gray-900">{{ $question->primarySubcategory->name }}</span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                @if($question->secondarySubcategory)
                                                    <span class="text-xs text-gray-400 block font-medium uppercase tracking-wider">{{ $question->secondarySubcategory->category->name }}</span>
                                                    <span class="font-medium text-gray-900">{{ $question->secondarySubcategory->name }}</span>
                                                @else
                                                    <span class="text-gray-400 italic">None</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                                {{ Str::limit($question->correct_answer_string, 20) ?: 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $question->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.questions.preview', $question) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Preview Question">
                                                    Preview
                                                </a>
                                                <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Edit">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Delete">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
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
                        // Concatenate text content to search universally across columns
                        const text = row.textContent.toLowerCase();
                        if (text.includes(query)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
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

                    // Re-append sorted rows to the table DOM
                    sortedRows.forEach(row => tableBody.appendChild(row));
                });
            });
        });
    </script>
</x-app-layout>