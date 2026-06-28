<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.codes.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Generate Access Code') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-red-800">Errors generating access code</h3>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.codes.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Code Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Code Type <span class="text-red-500">*</span></label>
                        <select name="type" id="code_type" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" required onchange="toggleCodeTypeFields()">
                            <option value="testing" {{ old('type') == 'testing' || !old('type') ? 'selected' : '' }}>Testing Session</option>
                            <option value="resource" {{ old('type') == 'resource' ? 'selected' : '' }}>Resource Link</option>
                        </select>
                    </div>

                    <!-- Target Test (Testing Only) -->
                    <div id="test_selection_container">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Target Test <span class="text-red-500">*</span></label>
                        <select name="test_id" id="test_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                            <option value="" disabled selected>Choose a generated test blueprint...</option>
                            @foreach($tests as $test)
                                <option value="{{ $test->id }}" {{ old('test_id', request('test_id')) == $test->id ? 'selected' : '' }}>
                                    {{ $test->name }} ({{ count($test->question_ids) }} questions)
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Students entering this access code will join sessions for the chosen test blueprint.</p>
                    </div>

                    <!-- Resource URL (Resource Only) -->
                    <div id="resource_url_container" style="display: none;">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Resource URL <span class="text-red-500">*</span></label>
                        <input type="url" name="resource_url" id="resource_url" value="{{ old('resource_url') }}" placeholder="https://example.com/some-resource" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                        <p class="mt-1 text-xs text-gray-500">Entering this code will redirect students directly to this external link.</p>
                    </div>

                    <!-- Expiration Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expires In <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <div class="flex space-x-3">
                            <div class="w-2/3">
                                <input type="number" name="expires_value" id="expires_value" value="{{ old('expires_value') }}" min="1" placeholder="e.g. 5" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                            </div>
                            <div class="w-1/3">
                                <select name="expires_unit" id="expires_unit" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                                    <option value="minutes" {{ old('expires_unit') == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                    <option value="hours" {{ old('expires_unit') == 'hours' || !old('expires_unit') ? 'selected' : '' }}>Hours</option>
                                    <option value="days" {{ old('expires_unit') == 'days' ? 'selected' : '' }}>Days</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Leave blank for a code that never expires. Otherwise, specify the duration after which the code will become invalid.</p>
                    </div>

                    <!-- Test Modifiers Checkboxes (Testing Only) -->
                    <div class="border-t border-gray-100 pt-6" id="rules_container">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Versatile Rules & Modifiers</h3>
                        
                        <div class="space-y-3 bg-gray-50 border border-gray-150 rounded-xl p-5">
                            <!-- Mix Questions -->
                            <label class="flex items-start cursor-pointer select-none">
                                <input type="checkbox" name="rules[mix_questions]" value="1" {{ old('rules.mix_questions', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Shuffle Questions</span>
                                    <span class="block text-xs text-gray-500">Randomize the order of questions for each student attempt.</span>
                                </div>
                            </label>

                            <!-- Mix Options -->
                            <label class="flex items-start cursor-pointer select-none border-t border-gray-100 pt-3">
                                <input type="checkbox" name="rules[mix_options]" value="1" {{ old('rules.mix_options', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Shuffle Choice Options</span>
                                    <span class="block text-xs text-gray-500">Randomize the order of selections (A, B, C, D) dynamically when presented.</span>
                                </div>
                            </label>

                            <!-- Hide After Submit -->
                            <label class="flex items-start cursor-pointer select-none border-t border-gray-100 pt-3">
                                <input type="checkbox" name="rules[hide_after_submit]" value="1" {{ old('rules.hide_after_submit') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Hide Exam Details Post-Submit</span>
                                    <span class="block text-xs text-gray-500">Prevent students from viewing the questions or choices immediately after clicking submit.</span>
                                </div>
                            </label>

                            <!-- View Answers After Submit -->
                            <label class="flex items-start cursor-pointer select-none border-t border-gray-100 pt-3">
                                <input type="checkbox" name="rules[view_answers_after_submit]" value="1" {{ old('rules.view_answers_after_submit', '1') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Allow Response Review Post-Submit</span>
                                    <span class="block text-xs text-gray-500">Allow students to review their selected answers after completing the test.</span>
                                </div>
                            </label>

                            <!-- View Correct Answers -->
                            <label class="flex items-start cursor-pointer select-none border-t border-gray-100 pt-3">
                                <input type="checkbox" name="rules[view_correct_answers]" value="1" {{ old('rules.view_correct_answers') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800">Highlight Correct Answers Post-Submit</span>
                                    <span class="block text-xs text-gray-500">Show the correct answer keys in green alongside the student's choices on their review sheet.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.codes.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow transition">
                            Generate Code Key
                        </button>
                    </div>
                </form>

                <script>
                    function toggleCodeTypeFields() {
                        const type = document.getElementById('code_type').value;
                        const testSelectionContainer = document.getElementById('test_selection_container');
                        const testIdSelect = document.getElementById('test_id');
                        const resourceUrlContainer = document.getElementById('resource_url_container');
                        const resourceUrlInput = document.getElementById('resource_url');
                        const rulesContainer = document.getElementById('rules_container');

                        if (type === 'testing') {
                            testSelectionContainer.style.display = 'block';
                            testIdSelect.required = true;
                            resourceUrlContainer.style.display = 'none';
                            resourceUrlInput.required = false;
                            rulesContainer.style.display = 'block';
                        } else {
                            testSelectionContainer.style.display = 'none';
                            testIdSelect.required = false;
                            resourceUrlContainer.style.display = 'block';
                            resourceUrlInput.required = true;
                            rulesContainer.style.display = 'none';
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        toggleCodeTypeFields();
                    });
                </script>

            </div>
        </div>
    </div>
</x-app-layout>
