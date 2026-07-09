<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 dark:text-neutral-400 hover:text-gray-900 dark:hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('Edit Subject Category') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 dark:border-zinc-800/60 bg-gray-50/50 dark:bg-zinc-950/20">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">Modify Category</h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-450 mt-1">Update the category name and default testing configurations.</p>
                </div>

                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="text-xs font-bold text-gray-400 dark:text-neutral-400 uppercase tracking-widest">Category Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $category->name) }}" 
                            required
                            placeholder="e.g. databases, networking, algorithms"
                            class="block w-full border-gray-200 dark:border-zinc-850 dark:bg-zinc-950/40 rounded-xl p-3.5 text-sm text-gray-700 dark:text-white font-medium focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Default Test Size -->
                        <div class="space-y-1.5">
                            <label for="default_test_size" class="text-xs font-bold text-gray-400 dark:text-neutral-400 uppercase tracking-widest">Default Question Count</label>
                            <input 
                                type="number" 
                                name="default_test_size" 
                                id="default_test_size" 
                                value="{{ old('default_test_size', $category->default_test_size) }}"
                                min="1"
                                max="100"
                                class="block w-full border-gray-200 dark:border-zinc-850 dark:bg-zinc-950/40 rounded-xl p-3.5 text-sm text-gray-700 dark:text-white font-medium focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            >
                            <span class="text-xxs text-gray-400">Default number of questions selected when generating exams from this category.</span>
                            @error('default_test_size')
                                <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Default Test Time -->
                        <div class="space-y-1.5">
                            <label for="default_test_time" class="text-xs font-bold text-gray-400 dark:text-neutral-400 uppercase tracking-widest">Default Duration (Minutes)</label>
                            <input 
                                type="number" 
                                name="default_test_time" 
                                id="default_test_time" 
                                value="{{ old('default_test_time', $category->default_test_time) }}"
                                min="1"
                                max="300"
                                class="block w-full border-gray-200 dark:border-zinc-850 dark:bg-zinc-950/40 rounded-xl p-3.5 text-sm text-gray-700 dark:text-white font-medium focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            >
                            <span class="text-xxs text-gray-400">Default countdown timer allocated to tests generated from this category.</span>
                            @error('default_test_time')
                                <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-between">
                        <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-250 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition shadow-sm">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow transition duration-150">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
