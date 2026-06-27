<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Student Access Codes') }}
            </h2>
            <a href="{{ route('admin.codes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Generate Access Code
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
                    @if($codes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No access codes found</h3>
                            <p class="mt-1 text-sm text-gray-500">Create an access code pointing to a test for students to join test sessions.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.codes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">
                                    Generate First Code
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Access Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target Test</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expires At</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rules / Modifiers</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($codes as $code)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-mono font-black text-2xl tracking-wider text-blue-600 bg-blue-50/50 px-3 py-1.5 rounded-lg border border-blue-100 select-all">
                                                    {{ $code->code }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                                {{ $code->test ? $code->test->name : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($code->isValid())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                                        Expired
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $code->expires_at ? $code->expires_at->format('M d, Y h:i A') : 'Never' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($code->rules as $rule => $active)
                                                        @if($active)
                                                            @php
                                                                $ruleNames = [
                                                                    'mix_questions' => 'Shuffle Questions',
                                                                    'mix_options' => 'Shuffle Options',
                                                                    'hide_after_submit' => 'Hide Test Post-Submit',
                                                                    'view_answers_after_submit' => 'View Answers Post-Submit',
                                                                    'view_correct_answers' => 'Show Key Post-Submit',
                                                                ];
                                                                $label = $ruleNames[$rule] ?? $rule;
                                                            @endphp
                                                            <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-600 rounded text-xxs font-semibold">
                                                                {{ $label }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('admin.codes.destroy', $code) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this access code? Students will no longer be able to use it to join.');">
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
</x-app-layout>
