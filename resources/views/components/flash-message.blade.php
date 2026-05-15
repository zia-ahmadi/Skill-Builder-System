@props(['message', 'type' => 'success'])

@if ($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed top-20 right-4 z-50 max-w-md w-full"
        role="alert"
        aria-live="polite"
    >
        <div class="bg-slate-900/95 border border-slate-800 rounded-lg shadow-xl p-4 flex items-start gap-3">
            @if ($type === 'success')
                <div class="flex-shrink-0 text-green-400 text-xl">✓</div>
                <div class="flex-1">
                    <p class="text-white font-medium">{{ $message }}</p>
                </div>
            @elseif ($type === 'error')
                <div class="flex-shrink-0 text-red-400 text-xl">✕</div>
                <div class="flex-1">
                    <p class="text-white font-medium">{{ $message }}</p>
                </div>
            @else
                <div class="flex-shrink-0 text-indigo-400 text-xl">ℹ</div>
                <div class="flex-1">
                    <p class="text-white font-medium">{{ $message }}</p>
                </div>
            @endif
            <button
                @click="show = false"
                class="flex-shrink-0 text-slate-400 hover:text-white transition-colors"
                aria-label="Close notification"
            >
                <span class="text-xl">&times;</span>
            </button>
        </div>
    </div>
@endif

