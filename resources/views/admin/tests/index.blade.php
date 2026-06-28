<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Test Blueprints') }}
            </h2>
            <a href="{{ route('admin.tests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Generate Random Test
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
                    @if($tests->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No tests generated</h3>
                            <p class="mt-1 text-sm text-gray-500">Create a test blueprint by selecting counts of random questions from specific subcategories.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.tests.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">
                                    Generate First Test
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Test Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Number of Questions</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($tests as $test)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                                #{{ $test->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $test->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-blue-50 text-blue-700 border-blue-100">
                                                    {{ count($test->question_ids) }} Questions
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('admin.tests.toggle', $test) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border transition duration-150 cursor-pointer shadow-sm {{ $test->is_active ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' }}" title="Click to toggle active status">
                                                        {{ $test->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $test->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.tests.preview', $test) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Preview Full Test">
                                                    Preview Test
                                                </a>
                                                <a href="{{ route('admin.codes.create', ['test_id' => $test->id]) }}" class="inline-flex items-center text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition" title="Create Access Code">
                                                    Create Code
                                                </a>
                                                <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this test? Any active access codes for it will be deleted.');">
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
