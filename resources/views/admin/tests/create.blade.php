<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.tests.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Generate Random Test') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($errors->any())
                <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Errors generating test</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.tests.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Test Details Card -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Test Settings</h3>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Test Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="e.g. Midterm Databases Exam 2026" required>
                    </div>
                </div>

                <!-- Categories Grid -->
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Select Question Pool Counts</h3>
                        <p class="text-sm text-gray-500 mt-1">Specify how many random questions you would like to pull from each subcategory.</p>
                    </div>

                    <div class="space-y-6">
                        @foreach($categories as $category)
                            <div class="border border-gray-150 rounded-xl overflow-hidden">
                                <div class="bg-gray-50/70 border-b border-gray-150 px-5 py-3">
                                    <h4 class="font-bold text-sm text-blue-800 uppercase tracking-wider">
                                        Category: {{ $category->name }}
                                    </h4>
                                </div>
                                <div class="p-5 divide-y divide-gray-100">
                                    @foreach($category->subcategories as $sub)
                                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                            <div class="w-2/3">
                                                <span class="font-semibold text-gray-800 text-sm md:text-base block">{{ $sub->name }}</span>
                                                <span class="text-xs text-gray-400 font-medium">
                                                    {{ $sub->primary_questions_count }} questions available in pool
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-3">
                                                <label class="text-xs font-semibold text-gray-500">Draw:</label>
                                                <input 
                                                    type="number" 
                                                    name="questions[{{ $sub->id }}]" 
                                                    value="{{ old('questions.'.$sub->id, 0) }}"
                                                    min="0" 
                                                    max="{{ $sub->primary_questions_count }}"
                                                    class="count-input w-20 border-gray-300 rounded-lg text-center font-bold text-gray-800 focus:ring-blue-500 focus:border-blue-500 p-1.5"
                                                    onchange="updateTotalSelected()"
                                                    onkeyup="updateTotalSelected()"
                                                >
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total Selected & Action Panel -->
                <div class="bg-gray-800 rounded-xl border border-gray-900 p-6 flex flex-col sm:flex-row justify-between items-center text-white shadow-md gap-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Summary of Selection</span>
                        <div class="text-xl font-bold mt-1">
                            Total Questions Chosen: <span id="total-selected-count" class="text-amber-400 text-2xl font-black">0</span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('admin.tests.index') }}" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-650 text-gray-200 text-sm font-semibold rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow transition">
                            Generate Test Blueprint
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- JS for live count tallies -->
    <script>
        function updateTotalSelected() {
            let total = 0;
            const inputs = document.querySelectorAll('.count-input');
            inputs.forEach(input => {
                const val = parseInt(input.value) || 0;
                // clamp value to max pool limit
                const max = parseInt(input.getAttribute('max')) || 0;
                let cleanVal = val;
                if (val < 0) {
                    cleanVal = 0;
                    input.value = 0;
                } else if (val > max) {
                    cleanVal = max;
                    input.value = max;
                }
                total += cleanVal;
            });
            document.getElementById('total-selected-count').innerText = total;
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateTotalSelected();
        });
    </script>
</x-app-layout>
