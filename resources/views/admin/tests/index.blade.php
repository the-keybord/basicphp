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
                <!-- Title & Generate Test Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-lg text-gray-800 leading-tight border-b border-gray-100 pb-3">
                        Test Blueprints
                    </h2>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.tests.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Generate Random Test
                        </a>
                    </div>
                </div>

                <!-- Status Metadata Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-455 uppercase tracking-widest block border-b border-gray-100 pb-2">Status Overview</span>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Total Blueprints:</span>
                        <span class="font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ count($tests) }}</span>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Table area) -->
            <main class="flex-grow w-full min-w-0 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-gray-900">
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
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Test Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Number of Questions</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($tests as $test)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                            #{{ $test->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                             <div class="flex items-center space-x-1.5">
                                                 <!-- Preview Test -->
                                                 <a href="{{ route('admin.tests.preview', $test) }}" class="inline-flex items-center p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg border border-transparent hover:border-blue-100 transition" title="Preview Test Blueprint">
                                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                     </svg>
                                                 </a>
                                                 <!-- Create Access Code -->
                                                 <a href="{{ route('admin.codes.create', ['test_id' => $test->id]) }}" class="inline-flex items-center p-1.5 text-green-600 hover:bg-green-50 rounded-lg border border-transparent hover:border-green-100 transition" title="Generate Access Code Key">
                                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                     </svg>
                                                 </a>
                                                 <!-- Delete -->
                                                 <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this test? Any active access codes for it will be deleted.');">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="inline-flex items-center p-1.5 text-red-600 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-100 transition" title="Delete Test">
                                                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                         </svg>
                                                     </button>
                                                 </form>
                                             </div>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>
