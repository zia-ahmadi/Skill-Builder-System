<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">
                    Skills Builder Dashboard
                </h2>
                <p class="text-sm text-gray-400 mt-1">Master your skills with 10,000 hours of deliberate practice</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Lifetime Mastery Goal Card (10,000 Hours) - Enhanced -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 rounded-2xl blur-xl opacity-20 group-hover:opacity-30 transition duration-1000"></div>
                <div class="relative bg-gradient-to-r from-violet-600/30 via-purple-600/30 to-fuchsia-600/30 border-2 border-violet-500/40 rounded-2xl shadow-2xl p-8 overflow-hidden">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl -mr-48 -mt-48"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-fuchsia-500/20 rounded-full blur-3xl -ml-48 -mb-48"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                                    <span class="text-3xl">🎯</span>
                                    Path to Mastery
                                </h3>
                                <p class="text-sm text-slate-300 font-medium">10,000 Hours to Master on Data</p>
                            </div>
                            <div class="text-6xl transform transition-transform group-hover:scale-110">
                                @if($lifetimeProgress >= 100) 👑
                                @elseif($lifetimeProgress >= 75) 🌟
                                @elseif($lifetimeProgress >= 50) ⭐
                                @elseif($lifetimeProgress >= 25) 💎
                                @else 🔥
                                @endif
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="flex items-baseline gap-4 mb-3">
                                <div class="text-5xl font-bold text-white drop-shadow-lg">{{ number_format($lifetimeTotal, 1) }}</div>
                                <div class="text-3xl text-slate-400">/</div>
                                <div class="text-4xl font-bold text-slate-200">{{ number_format($lifetimeGoal, 0) }}</div>
                                <div class="text-xl text-slate-400 font-medium">hours</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-base font-semibold text-slate-300">
                                    {{ number_format($lifetimeProgress, 2) }}% complete
                                </div>
                                @if($lifetimeRemaining > 0)
                                    <span class="text-slate-500">•</span>
                                    <div class="text-base text-slate-400">
                                        {{ number_format($lifetimeRemaining, 0) }} hours to mastery
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="w-full h-8 bg-slate-800/80 rounded-full overflow-hidden mb-6 shadow-inner backdrop-blur-sm">
                            <div class="h-full bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 transition-all duration-1000 ease-out relative progress-bar" 
                                 style="width: 0%" data-progress="{{ min(100, $lifetimeProgress) }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                <div class="absolute right-0 top-0 h-full w-2 bg-white/50 blur-sm"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="p-4 bg-slate-900/60 backdrop-blur-sm rounded-xl border border-slate-700/60 shadow-lg hover:bg-slate-900/80 transition-colors">
                                <div class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Remaining</div>
                                <div class="text-2xl font-bold text-white">{{ number_format($lifetimeRemaining, 0) }} hrs</div>
                            </div>
                            @if($estimatedDaysToMastery)
                            <div class="p-4 bg-slate-900/60 backdrop-blur-sm rounded-xl border border-slate-700/60 shadow-lg hover:bg-slate-900/80 transition-colors">
                                <div class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Estimated Days</div>
                                <div class="text-2xl font-bold text-white">{{ number_format($estimatedDaysToMastery) }}</div>
                            </div>
                            @endif
                            @if($estimatedDateToMastery)
                            <div class="p-4 bg-slate-900/60 backdrop-blur-sm rounded-xl border border-slate-700/60 shadow-lg hover:bg-slate-900/80 transition-colors">
                                <div class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Target Date</div>
                                <div class="text-2xl font-bold text-white">{{ $estimatedDateToMastery->format('M Y') }}</div>
                            </div>
                            @endif
                        </div>
                        @if(isset($lifetimeEncouragementMessage))
                            <div class="p-5 rounded-xl bg-slate-900/70 backdrop-blur-sm border border-slate-700/60 shadow-lg">
                                <p class="text-base text-slate-200 leading-relaxed font-medium">
                                    {{ $lifetimeEncouragementMessage['message'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daily, Weekly, Monthly Goals - Enhanced Professional Style -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily Goal -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-gradient-to-br from-indigo-600/25 via-purple-600/25 to-indigo-700/25 border-2 border-indigo-500/40 rounded-2xl shadow-xl p-6 backdrop-blur-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">Daily Learning Goal</h3>
                                <p class="text-base font-semibold text-indigo-200">{{ number_format($todayTotal, 1) }} / {{ number_format($dailyGoal, 1) }} hours</p>
                            </div>
                            <div class="text-6xl transform transition-transform group-hover:scale-110">
                                @if($todayTotal >= $dailyGoal) 🎉
                                @elseif($dailyProgress >= 75) 🚀
                                @elseif($dailyProgress >= 50) 💪
                                @else 📚
                                @endif
                            </div>
                        </div>
                        <div class="w-full h-5 bg-slate-800/80 rounded-full overflow-hidden mb-4 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-400 transition-all duration-700 ease-out progress-bar" 
                                 style="width: 0%" data-progress="{{ min(100, $dailyProgress) }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            @if($dailyRemaining > 0)
                                <span class="text-sm font-semibold text-slate-300">
                                    <span class="text-white font-bold">{{ number_format($dailyRemaining, 1) }} hours</span> remaining
                                </span>
                            @else
                                <span class="text-sm font-bold text-green-400">
                                    Goal achieved! 🎊
                                </span>
                            @endif
                            <span class="text-sm font-bold text-slate-400 bg-slate-800/60 px-3 py-1 rounded-full">{{ number_format($dailyProgress, 0) }}%</span>
                        </div>
                        <div class="pt-4 border-t border-indigo-500/30">
                            <div class="text-xs font-medium text-indigo-300 mb-3">
                                {{ $todaySessionsCount }} session{{ $todaySessionsCount !== 1 ? 's' : '' }} today
                            </div>
                            @if(isset($dailyEncouragementMessage))
                            <div class="p-3 rounded-lg bg-slate-900/60 backdrop-blur-sm border border-indigo-500/20">
                                <p class="text-xs text-slate-200 leading-relaxed">{{ $dailyEncouragementMessage['message'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Weekly Goal -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-gradient-to-br from-green-600/25 via-emerald-600/25 to-green-700/25 border-2 border-green-500/40 rounded-2xl shadow-xl p-6 backdrop-blur-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">Weekly Learning Goal</h3>
                                <p class="text-base font-semibold text-green-200">{{ number_format($weekTotal, 1) }} / {{ number_format($weeklyGoal, 1) }} hours</p>
                            </div>
                            <div class="text-6xl transform transition-transform group-hover:scale-110">
                                @if($weekTotal >= $weeklyGoal) 🎉
                                @elseif($weeklyProgress >= 75) 🚀
                                @elseif($weeklyProgress >= 50) 💪
                                @else 📅
                                @endif
                            </div>
                        </div>
                        <div class="w-full h-5 bg-slate-800/80 rounded-full overflow-hidden mb-4 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-green-500 via-emerald-500 to-green-400 transition-all duration-700 ease-out progress-bar" 
                                 style="width: 0%" data-progress="{{ min(100, $weeklyProgress) }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            @if($weeklyRemaining > 0)
                                <span class="text-sm font-semibold text-slate-300">
                                    <span class="text-white font-bold">{{ number_format($weeklyRemaining, 1) }} hours</span> remaining
                                </span>
                            @else
                                <span class="text-sm font-bold text-green-400">
                                    Goal achieved! 🎊
                                </span>
                            @endif
                            <span class="text-sm font-bold text-slate-400 bg-slate-800/60 px-3 py-1 rounded-full">{{ number_format($weeklyProgress, 0) }}%</span>
                        </div>
                        <div class="pt-4 border-t border-green-500/30">
                            <div class="text-xs font-medium text-green-300 mb-3">
                                {{ $weekSessionsCount }} session{{ $weekSessionsCount !== 1 ? 's' : '' }} this week
                            </div>
                            @if(isset($weeklyEncouragementMessage))
                            <div class="p-3 rounded-lg bg-slate-900/60 backdrop-blur-sm border border-green-500/20">
                                <p class="text-xs text-slate-200 leading-relaxed">{{ $weeklyEncouragementMessage['message'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Monthly Goal -->
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative bg-gradient-to-br from-amber-600/25 via-orange-600/25 to-amber-700/25 border-2 border-amber-500/40 rounded-2xl shadow-xl p-6 backdrop-blur-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">Monthly Learning Goal</h3>
                                <p class="text-base font-semibold text-amber-200">{{ number_format($monthTotal, 1) }} / {{ number_format($monthlyGoal, 1) }} hours</p>
                            </div>
                            <div class="text-6xl transform transition-transform group-hover:scale-110">
                                @if($monthTotal >= $monthlyGoal) 🎉
                                @elseif($monthlyProgress >= 75) 🚀
                                @elseif($monthlyProgress >= 50) 💪
                                @else 📈
                                @endif
                            </div>
                        </div>
                        <div class="w-full h-5 bg-slate-800/80 rounded-full overflow-hidden mb-4 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-amber-500 via-orange-500 to-amber-400 transition-all duration-700 ease-out progress-bar" 
                                 style="width: 0%" data-progress="{{ min(100, $monthlyProgress) }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            @if($monthlyRemaining > 0)
                                <span class="text-sm font-semibold text-slate-300">
                                    <span class="text-white font-bold">{{ number_format($monthlyRemaining, 1) }} hours</span> remaining
                                </span>
                            @else
                                <span class="text-sm font-bold text-green-400">
                                    Goal achieved! 🎊
                                </span>
                            @endif
                            <span class="text-sm font-bold text-slate-400 bg-slate-800/60 px-3 py-1 rounded-full">{{ number_format($monthlyProgress, 0) }}%</span>
                        </div>
                        <div class="pt-4 border-t border-amber-500/30">
                            <div class="text-xs font-medium text-amber-300 mb-3">
                                {{ \Carbon\Carbon::now()->format('F') }} progress
                            </div>
                            @if(isset($monthlyEncouragementMessage))
                            <div class="p-3 rounded-lg bg-slate-900/60 backdrop-blur-sm border border-amber-500/20">
                                <p class="text-xs text-slate-200 leading-relaxed">{{ $monthlyEncouragementMessage['message'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Statistics Grid - Enhanced -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-indigo-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Total Skills</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ $totalSkills }}</div>
                            <div class="text-xs font-medium text-indigo-400">
                                {{ $activeSkills }} active
                            </div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">🎯</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-purple-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Total Hours</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($lifetimeTotal, 1) }}</div>
                            <div class="text-xs font-medium text-purple-400">
                                Lifetime total
                            </div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📚</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-blue-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">This Week</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($weekTotal, 1) }}</div>
                            <div class="text-xs font-medium text-blue-400">
                                hours studied
                            </div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📅</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-emerald-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Avg Daily</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($averageDailyHours, 1) }}</div>
                            <div class="text-xs font-medium text-emerald-400">
                                hours this week
                            </div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📈</div>
                    </div>
                </div>
            </div>

            <!-- Skills List - Enhanced -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-5 flex justify-between items-center border-b border-slate-700/50 bg-slate-900/50 backdrop-blur-sm">
                    <div>
                        <h3 class="text-xl font-bold text-white">Your Skills</h3>
                        <p class="text-sm text-slate-400 mt-1 font-medium">Track progress and manage your learning goals</p>
                    </div>
                    <a href="{{ route('skills.index') }}" class="px-5 py-2.5 bg-indigo-600/20 hover:bg-indigo-600/40 border border-indigo-500/30 text-indigo-300 rounded-lg text-sm font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                        View All
                    </a>
                </div>
                <div class="divide-y divide-slate-800/50">
                    @forelse($skills as $skill)
                        @php
                            $remaining = max(0, $skill->goal_hours - ($skill->total_hours ?? 0));
                            $pct = $skill->goal_hours > 0 ? min(100, round((($skill->total_hours ?? 0) / $skill->goal_hours) * 100)) : 0;
                        @endphp
                        <div class="px-6 py-5 hover:bg-slate-800/40 transition-all duration-300 group">
                            <div class="flex items-start gap-5">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-3">
                                        <a href="{{ route('skills.show', $skill) }}" class="text-xl font-bold text-white hover:text-indigo-300 transition-colors group-hover:translate-x-1 inline-block">
                                            {{ $skill->name }}
                                        </a>
                                        @if($skill->deadline)
                                            @php
                                                $deadlineDate = \Carbon\Carbon::parse($skill->deadline);
                                                $isPast = $deadlineDate->isPast();
                                                $daysUntilDeadline = $deadlineDate->diffInDays(\Carbon\Carbon::now(), false);
                                            @endphp
                                            <span class="text-xs px-3 py-1.5 rounded-full font-semibold {{ $isPast ? 'bg-red-500/20 text-red-200 border border-red-500/30' : ($daysUntilDeadline <= 7 ? 'bg-amber-500/20 text-amber-200 border border-amber-500/30' : 'bg-blue-500/20 text-blue-200 border border-blue-500/30') }}">
                                                @if($isPast)
                                                    Overdue
                                                @elseif($daysUntilDeadline == 0)
                                                    Due Today
                                                @elseif($daysUntilDeadline == 1)
                                                    Due Tomorrow
                                                @else
                                                    {{ $daysUntilDeadline }} days left
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm mb-3">
                                        <span class="text-slate-300 font-medium">
                                            <span class="font-bold text-white text-base">{{ number_format($skill->total_hours ?? 0, 1) }}</span> / 
                                            <span class="text-slate-400">{{ number_format($skill->goal_hours, 1) }}</span> hrs
                                        </span>
                                        <span class="text-slate-600">•</span>
                                        <span class="text-slate-400 font-medium">{{ $pct }}% complete</span>
                                        @if($remaining > 0)
                                            <span class="text-slate-600">•</span>
                                            <span class="text-slate-500 text-xs">{{ number_format($remaining, 1) }} hrs remaining</span>
                                        @endif
                                    </div>
                                    <div class="w-full h-3 bg-slate-800/80 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-400 transition-all duration-700 ease-out relative progress-bar" 
                                             style="width: 0%" data-progress="{{ $pct }}">
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                        </div>
                                    </div>
                                    @if($skill->description)
                                        <p class="text-xs text-slate-500 mt-3 line-clamp-1 italic">{{ $skill->description }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('skills.show', $skill) }}" class="px-5 py-2.5 text-sm bg-indigo-600/20 hover:bg-indigo-600/40 border border-indigo-500/30 text-indigo-300 rounded-lg font-semibold transition-all duration-300 hover:scale-105 whitespace-nowrap backdrop-blur-sm">
                                    Open →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-20 text-center">
                            <div class="text-7xl mb-5 animate-pulse">🎯</div>
                            <h3 class="text-xl font-bold text-white mb-3">No skills yet</h3>
                            <p class="text-slate-400 mb-8 max-w-md mx-auto">Create your first skill to start your journey to mastery!</p>
                            <a href="{{ route('skills.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                Create Your First Skill
                            </a>
                        </div>
                    @endforelse
                </div>
                @if($skills->count() > 0 && $skills->count() >= 5)
                    <div class="px-6 py-4 border-t border-slate-800/50 text-center bg-slate-900/30 backdrop-blur-sm">
                        <a href="{{ route('skills.index') }}" class="text-indigo-300 hover:text-indigo-200 text-sm font-semibold transition-colors inline-flex items-center gap-2">
                            View All {{ $totalSkills }} Skills
                            <span class="text-lg">→</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.progress-bar[data-progress]').forEach(function (el) {
            var value = parseFloat(el.getAttribute('data-progress'));
            if (!isNaN(value)) {
                el.style.width = value + '%';
            }
        });
    </script>
</x-app-layout>
