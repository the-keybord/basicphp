<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Alert Banner -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/20 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center text-green-800 dark:text-green-300">
                <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Left Sidebar -->
            <aside class="w-full md:w-80 md:sticky md:top-6 space-y-6 flex-shrink-0">
                <!-- Title & Add Category Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-lg text-gray-800 leading-tight border-b border-gray-100 pb-3">
                        Categories
                    </h2>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.categories.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Category
                        </a>
                    </div>
                </div>

                <!-- Status Metadata Card -->
                <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-450 uppercase tracking-widest block border-b border-gray-100 pb-2">Status Overview</span>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Total Categories:</span>
                        <span class="font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ count($categories) }}</span>
                    </div>
                </div>
            </aside>

            <!-- Right Main Column (Category Grid) -->
            <main class="flex-grow w-full min-w-0">

            @if($categories->isEmpty())
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">No categories configured</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Get started by creating a subject category to map your XML questions to.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow">
                            Create First Category
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($categories as $category)
                        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800/80 rounded-2xl shadow-sm flex flex-col justify-between overflow-hidden">
                            <!-- Card Header -->
                            <div class="p-6 bg-gray-50/50 dark:bg-zinc-950/20 border-b border-gray-100 dark:border-zinc-800/60 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-black text-gray-900 dark:text-white capitalize">{{ $category->name }}</h3>
                                    <div class="flex gap-2 mt-1.5">
                                        <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 text-xxs font-bold rounded border border-blue-100 dark:border-blue-900">
                                            Size: {{ $category->default_test_size ?? 'N/A' }}
                                        </span>
                                        <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 text-xxs font-bold rounded border border-indigo-100 dark:border-indigo-900">
                                            Time: {{ $category->default_test_time ?? 'N/A' }} mins
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg border border-yellow-100 transition" title="Edit Category">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? Deleting this category will delete all its nested subcategories!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg border border-red-100 transition" title="Delete Category">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Nested Subcategories List -->
                            <div class="p-6 flex-grow space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subcategories</h4>
                                    <a href="{{ route('admin.subcategories.create', ['category_id' => $category->id]) }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Subcategory
                                    </a>
                                </div>

                                @if($category->subcategories->isEmpty())
                                    <p class="text-xs text-gray-400 italic">No subcategories defined for this category.</p>
                                @else
                                    <div class="divide-y divide-gray-100 dark:divide-zinc-800 border border-gray-100 dark:border-zinc-800/80 rounded-xl overflow-hidden bg-gray-50/20">
                                        @foreach($category->subcategories as $subcategory)
                                            <div class="p-3.5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-zinc-950/25 transition">
                                                <div class="flex-grow min-w-0 pr-4">
                                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-250 truncate block">{{ $subcategory->name }}</span>
                                                    <div class="flex gap-1.5 mt-1">
                                                        <span class="text-[10px] font-medium text-gray-400 bg-white dark:bg-zinc-900 border border-gray-150 dark:border-zinc-800 px-1.5 py-0.5 rounded">
                                                            Size: {{ $subcategory->default_test_size ?? 'N/A' }}
                                                        </span>
                                                        <span class="text-[10px] font-medium text-gray-400 bg-white dark:bg-zinc-900 border border-gray-150 dark:border-zinc-800 px-1.5 py-0.5 rounded">
                                                            Time: {{ $subcategory->default_test_time ?? 'N/A' }} mins
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-1.5 flex-shrink-0">
                                                    <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="p-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-md border border-yellow-100 transition" title="Edit Subcategory">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </a>
                                                    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 bg-red-50 hover:bg-red-100 text-red-700 rounded-md border border-red-100 transition" title="Delete Subcategory">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            </main>
        </div>
    </div>
</x-app-layout>
