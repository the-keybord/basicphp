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

                    <!-- Target Test -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Target Test <span class="text-red-500">*</span></label>
                        <select name="test_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" required>
                            <option value="" disabled selected>Choose a generated test blueprint...</option>
                            @foreach($tests as $test)
                                <option value="{{ $test->id }}" {{ old('test_id', request('test_id')) == $test->id ? 'selected' : '' }}>
                                    {{ $test->name }} ({{ count($test->question_ids) }} questions)
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Students entering this access code will join sessions for the chosen test blueprint.</p>
                    </div>

                    <!-- Expiration DateTime -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expiration Date & Time <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                        <p class="mt-1 text-xs text-gray-500">Leave blank for a code that never expires. Otherwise, the code will become invalid after this date and time.</p>
                    </div>

                    <!-- Test Modifiers Checkboxes -->
                    <div class="border-t border-gray-100 pt-6">
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

            </div>
        </div>
    </div>
</x-app-layout>
