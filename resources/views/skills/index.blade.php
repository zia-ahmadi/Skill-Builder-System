<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">Skills</h2>
                <p class="text-sm text-gray-400 mt-1">Manage skills and progress</p>
            </div>
            <a href="{{ route('skills.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                + New Skill
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if($skills->isEmpty())
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 via-purple-600 to-fuchsia-600 rounded-2xl blur-xl opacity-20"></div>
                    <div class="relative bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-16 text-center shadow-2xl backdrop-blur-sm">
                        <div class="text-7xl mb-6 animate-pulse">🎯</div>
                        <h3 class="text-2xl font-bold text-white mb-3">No skills yet</h3>
                        <p class="text-slate-400 mb-8 max-w-md mx-auto font-medium">Start by adding your first skill to track progress.</p>
                        <a href="{{ route('skills.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                            + New Skill
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($skills as $skill)
                        @php
                            $remaining = max(0, $skill->goal_hours - ($skill->total_hours ?? 0));
                            $pct = $skill->goal_hours > 0 ? min(100, round((($skill->total_hours ?? 0) / $skill->goal_hours) * 100)) : 0;
                        @endphp
                        <div class="group relative bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col backdrop-blur-sm hover:border-indigo-500/30">
                            <div class="relative h-48 bg-gradient-to-r from-indigo-900/60 via-slate-900/70 to-indigo-700/50 overflow-hidden">
                                @if($skill->banner_url)
                                    <img src="{{ $skill->banner_url }}" alt="{{ $skill->name }} banner" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/30 to-transparent"></div>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-800/80 via-purple-800/70 to-indigo-600/70"></div>
                                @endif
                                <div class="absolute bottom-4 left-5 right-5 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('skills.show', $skill) }}" class="text-xl font-bold text-white hover:text-indigo-200 transition-colors drop-shadow-lg">
                                            {{ $skill->name }}
                                        </a>
                                        @if($skill->deadline)
                                            @php
                                                $deadlineDate = \Carbon\Carbon::parse($skill->deadline)->startOfDay();
                                                $today = now()->startOfDay();
                                                $isPast = $deadlineDate->isPast();
                                                $daysDifference = $isPast ? 0 : (int) $today->diffInDays($deadlineDate, false);
                                            @endphp
                                            @if($isPast)
                                                <span class="text-xs px-3 py-1.5 rounded-full bg-red-500/30 text-red-200 border border-red-500/40 font-semibold backdrop-blur-sm">
                                                    Deadline Finished
                                                </span>
                                            @else
                                                <span class="text-xs px-3 py-1.5 rounded-full bg-amber-500/30 text-amber-200 border border-amber-500/40 font-semibold backdrop-blur-sm">
                                                    @if($daysDifference == 0)
                                                        Due Today
                                                    @elseif($daysDifference == 1)
                                                        Due in 1 day
                                                    @else
                                                        Due in {{ $daysDifference }} days
                                                    @endif
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    <span class="text-xs px-3 py-1.5 rounded-full bg-slate-900/80 text-slate-100 border border-slate-700/60 font-semibold backdrop-blur-sm">
                                        Remaining {{ number_format($remaining, 1) }}h
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-base font-semibold text-slate-300 mb-3">
                                        <span class="text-white font-bold text-lg">{{ number_format($skill->total_hours ?? 0, 1) }}</span> / 
                                        <span class="text-slate-400">{{ number_format($skill->goal_hours, 1) }}</span> hrs • 
                                        <span class="text-indigo-300 font-bold">{{ $pct }}%</span>
                                    </p>
                                    @if($skill->description)
                                        <p class="text-sm text-slate-400 line-clamp-2 italic mb-3">{{ $skill->description }}</p>
                                    @endif
                                </div>
                                <div>
                                    <div class="w-full h-3 bg-slate-800/80 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-400 transition-all duration-700 ease-out relative" 
                                             style="width: {{ $pct }}%">
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-between text-xs font-medium text-slate-400">
                                        <span>Goal: {{ number_format($skill->goal_hours, 1) }}h</span>
                                        <span>Done: {{ number_format($skill->total_hours ?? 0, 1) }}h</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-700/50">
                                    <div class="flex gap-4 text-sm">
                                        <a href="{{ route('skills.show', $skill) }}" class="text-indigo-300 hover:text-indigo-100 font-semibold transition-colors">View</a>
                                        <a href="{{ route('skills.edit', $skill) }}" class="text-indigo-400 hover:text-indigo-200 font-semibold transition-colors">Edit</a>
                                    </div>
                                    <form action="{{ route('skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Delete this skill?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-300 text-sm font-semibold transition-colors">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

