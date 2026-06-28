@props(['logoUrl' => '#', 'maxWidth' => 'max-w-6xl'])

<nav {{ $attributes->merge(['class' => 'fixed top-5 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] ' . $maxWidth . ' bg-white/90 dark:bg-[#212121]/90 backdrop-blur-md border border-slate-200 dark:border-neutral-800 rounded-[2rem] px-6 pl-0 py-1.5 z-50 flex items-center justify-between shadow-2xl text-slate-800 dark:text-white transition duration-300']) }}>
    <!-- Logo container -->
    <div class="shrink-0 flex items-center relative overflow-visible w-40 h-12">
        <a href="{{ $logoUrl }}" class="absolute -top-3 left-0 z-50 overflow-visible logo-toggle-trigger">
            <img src="{{ asset('images/zeceinfoblock.png') }}" alt="Zece Info" class="block h-16 w-auto transition-transform duration-200 transform scale-110 hover:scale-100 filter drop-shadow-md">
        </a>
    </div>

    <!-- Center/Main Slot -->
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="flex-grow flex items-center">
            {{ $slot }}
        </div>
    @endif

    <!-- Actions / Right Buttons Slot -->
    @if(isset($actions) && $actions->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</nav>
