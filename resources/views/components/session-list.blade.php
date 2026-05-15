@props(['sessions', 'variant' => 'default', 'showActions' => false, 'skill' => null])

@php
    $variant = $variant ?? 'default';
    $showActions = $showActions ?? false;
@endphp

<div class="divide-y divide-slate-800">
    @forelse($sessions as $session)
        @if($variant === 'compact')
            <!-- Compact variant for Dashboard -->
            <div class="px-6 py-4 flex justify-between">
                <div>
                    <div class="text-white font-semibold">
                        {{ $session->skill->name ?? 'Skill' }}
                        @if($session->topic)
                            <span class="text-slate-400 text-sm">• {{ $session->topic->name }}</span>
                        @endif
                    </div>
                    <div class="text-sm text-slate-400">
                        {{ $session->session_date->toDateString() }} • {{ number_format($session->hours, 1) }} hrs
                    </div>
                </div>
                @if($session->notes)
                    <div class="text-slate-400 text-sm">
                        {{ \Illuminate\Support\Str::limit($session->notes, 50) }}
                    </div>
                @endif
            </div>
        @elseif($variant === 'detailed')
            <!-- Detailed variant for Learned page -->
            <div class="flex items-start justify-between p-4 bg-slate-800/50 rounded-lg border border-slate-700/50 hover:border-slate-600 transition-colors">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h4 class="text-white font-semibold">{{ $session->skill->name }}</h4>
                        @if($session->topic)
                            <span class="text-slate-400 text-sm">•</span>
                            <span class="text-slate-300 text-sm">{{ $session->topic->name }}</span>
                        @endif
                    </div>
                    @if($session->notes)
                        <p class="text-sm text-slate-400 mb-2">{{ \Illuminate\Support\Str::limit($session->notes, 150) }}</p>
                    @endif
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <span>Logged: {{ $session->created_at->format('g:i A') }}</span>
                        @if($session->created_at->format('Y-m-d') !== $session->session_date->format('Y-m-d'))
                            <span>•</span>
                            <span>Session Date: {{ $session->session_date->format('M j, Y') }}</span>
                        @endif
                    </div>
                </div>
                <div class="ml-4 text-right">
                    <div class="text-2xl font-bold text-indigo-400">
                        {{ number_format($session->hours, 1) }}
                    </div>
                    <div class="text-xs text-slate-400 mt-1">hours</div>
                </div>
            </div>
        @elseif($variant === 'skill')
            <!-- Skill-specific variant -->
            <div class="py-3 flex items-start justify-between gap-3">
                <div>
                    <div class="text-white font-semibold">
                        {{ $session->session_date->toDateString() }} • {{ number_format($session->hours, 1) }} hrs
                    </div>
                    <div class="text-sm text-slate-400">
                        @if($session->topic) Topic: {{ $session->topic->name }} • @endif
                        {{ $session->notes }}
                    </div>
                </div>
                @if($showActions && $skill)
                    <form action="{{ route('skills.sessions.destroy', [$skill, $session]) }}" method="POST" onsubmit="return confirm('Delete session?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-400 hover:text-red-300 text-sm">Delete</button>
                    </form>
                @endif
            </div>
        @else
            <!-- Default variant -->
            <div class="px-6 py-4 flex justify-between">
                <div>
                    <div class="text-white font-semibold">
                        {{ $session->skill->name ?? 'Skill' }}
                        @if($session->topic)
                            <span class="text-slate-400 text-sm">• {{ $session->topic->name }}</span>
                        @endif
                    </div>
                    <div class="text-sm text-slate-400">
                        {{ $session->session_date->toDateString() }} • {{ number_format($session->hours, 1) }} hrs
                    </div>
                </div>
                @if($session->notes)
                    <div class="text-slate-400 text-sm">
                        {{ \Illuminate\Support\Str::limit($session->notes, 50) }}
                    </div>
                @endif
            </div>
        @endif
    @empty
        <div class="px-6 py-10 text-center text-slate-400">
            {{ $emptyMessage ?? 'No sessions yet.' }}
        </div>
    @endforelse
</div>

