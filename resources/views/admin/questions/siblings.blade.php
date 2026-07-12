<x-app-layout title="Sister Questions Manager">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Sister Questions Manager') }}
            </h2>
        </div>
    </x-slot>

    @php
        $pendingCount = collect($proposals)->where('is_linked', false)->count();
        $linkedCount = collect($proposals)->where('is_linked', true)->count();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Alert Banner -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6 items-start">
                
                <!-- Left Info Panel (Sidebar) -->
                <aside class="w-full lg:w-80 space-y-6 flex-shrink-0 lg:sticky lg:top-6">
                    <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">About Sibling Questions</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Sister questions are questions within the same subcategory that have highly similar text bodies.
                        </p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Linking questions prevents them from being drawn on the same test, ensuring students get diverse topics and are not quizzed on duplicate contents.
                        </p>
                        <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 text-purple-850 text-xxs font-medium leading-normal space-y-1">
                            <span class="font-bold block text-[10px] uppercase text-purple-700">How recommendation works:</span>
                            <span>• Computes similarity index of question pairs.</span>
                            <span>• Proposes pairs with similarity scores &ge; 75%.</span>
                            <span>• Rejecting a pair prevents it from ever being proposed again.</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-sm space-y-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block border-b border-gray-100 pb-2">Matches Count</span>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 font-medium">Pending:</span>
                            <span class="font-bold text-purple-700 bg-purple-50 border border-purple-150 px-2 py-0.5 rounded-md">{{ $pendingCount }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1">
                            <span class="text-gray-500 font-medium">Active Linked Pairs:</span>
                            <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-150 px-2 py-0.5 rounded-md">{{ $linkedCount }}</span>
                        </div>
                    </div>
                </aside>

                <!-- Main Content Panel (Proposals list) -->
                <main class="flex-grow w-full space-y-6">
                    @if(empty($proposals))
                        <div class="bg-white rounded-xl border border-gray-150 p-12 text-center shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-base font-bold text-gray-800">All caught up!</h3>
                            <p class="text-xs text-gray-500 mt-1">No pending sister question recommendations detected in your database.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($proposals as $prop)
                                <div class="bg-white rounded-2xl border overflow-hidden shadow-sm hover:shadow transition duration-200 {{ $prop['is_linked'] ? 'border-purple-200 ring-2 ring-purple-100/50' : 'border-gray-150' }}">
                                    
                                    <!-- Proposal Header -->
                                    <div class="border-b px-5 py-3 flex items-center justify-between flex-wrap gap-2 text-xs {{ $prop['is_linked'] ? 'bg-purple-50/50 border-purple-100' : 'bg-gray-50/50 border-gray-150' }}">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-400 font-medium">Category:</span>
                                            <span class="font-bold text-gray-700 bg-white px-2 py-0.5 border border-gray-200 rounded">{{ $prop['category_name'] }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($prop['is_linked'])
                                                <span class="text-[10px] font-extrabold text-emerald-700 uppercase bg-emerald-50 border border-emerald-250 px-2 py-0.5 rounded-full shadow-sm">
                                                    Sisters Linked
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold text-purple-700 uppercase bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full shadow-sm">
                                                    {{ $prop['similarity'] }}% Content Match
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Comparison Grid -->
                                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5 divide-y md:divide-y-0 md:divide-x divide-gray-150">
                                        
                                        <!-- Question 1 -->
                                        <div class="space-y-3 pb-3 md:pb-0">
                                            <div class="flex items-center justify-between text-xs">
                                                <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                    <span class="text-[10px] font-mono font-bold text-gray-500 px-1 py-0.5 bg-gray-100 border border-gray-200 rounded">ID: {{ $prop['q1']->id }}</span>
                                                    <span class="text-[8px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100">
                                                        {{ str_replace('_', ' ', $prop['q1']->question_type) }}
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-500 bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm" title="Subcategory">
                                                        {{ $prop['q1_sub_name'] }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('admin.questions.preview', $prop['q1']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition flex items-center gap-1 font-bold text-[10px]" title="Preview Question">
                                                    <span>View Details</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <p class="text-xs text-gray-700 leading-relaxed italic bg-gray-50/50 p-3.5 rounded-xl border border-gray-150/70 font-serif">
                                                "{{ $prop['text1'] }}"
                                            </p>
                                        </div>

                                        <!-- Question 2 -->
                                        <div class="space-y-3 pt-3 md:pt-0 md:pl-5">
                                            <div class="flex items-center justify-between text-xs">
                                                <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                                    <span class="text-[10px] font-mono font-bold text-gray-500 px-1 py-0.5 bg-gray-100 border border-gray-200 rounded">ID: {{ $prop['q2']->id }}</span>
                                                    <span class="text-[8px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100">
                                                        {{ str_replace('_', ' ', $prop['q2']->question_type) }}
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-500 bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm" title="Subcategory">
                                                        {{ $prop['q2_sub_name'] }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('admin.questions.preview', $prop['q2']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition flex items-center gap-1 font-bold text-[10px]" title="Preview Question">
                                                    <span>View Details</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <p class="text-xs text-gray-700 leading-relaxed italic bg-gray-50/50 p-3.5 rounded-xl border border-gray-150/70 font-serif">
                                                "{{ $prop['text2'] }}"
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons Footer -->
                                    <div class="bg-gray-50/30 border-t border-gray-150 px-5 py-3.5 flex items-center justify-end space-x-3">
                                        @if($prop['is_linked'])
                                            <!-- Unpair Sibling -->
                                            <form action="{{ route('admin.questions.siblings.unpair') }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="q1_id" value="{{ $prop['q1']->id }}">
                                                <input type="hidden" name="q2_id" value="{{ $prop['q2']->id }}">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold uppercase rounded-lg shadow-sm transition cursor-pointer">
                                                    Unpair Questions
                                                </button>
                                            </form>
                                        @else
                                            <!-- Reject Proposal -->
                                            <form action="{{ route('admin.questions.siblings.reject') }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="q1_id" value="{{ $prop['q1']->id }}">
                                                <input type="hidden" name="q2_id" value="{{ $prop['q2']->id }}">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold uppercase rounded-lg shadow-sm transition cursor-pointer">
                                                    Dismiss Proposal
                                                </button>
                                            </form>

                                            <!-- Accept Proposal -->
                                            <form action="{{ route('admin.questions.siblings.accept') }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="q1_id" value="{{ $prop['q1']->id }}">
                                                <input type="hidden" name="q2_id" value="{{ $prop['q2']->id }}">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase rounded-lg shadow-sm transition cursor-pointer">
                                                    Link as Sisters
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
