<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">
                    Learning Analytics
                </h2>
                <p class="text-sm text-gray-400 mt-1">Track your learning progress and insights</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Date Range Filter - Enhanced -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl p-6 backdrop-blur-sm">
                <form method="GET" action="{{ route('analytics.index') }}" class="space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label for="date_from" class="block text-sm font-semibold text-slate-300 mb-2">From Date</label>
                            <input 
                                type="date" 
                                id="date_from"
                                name="date_from" 
                                value="{{ $dateFrom->format('Y-m-d') }}" 
                                class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                aria-label="Start date"
                            >
                        </div>
                        <div class="flex-1">
                            <label for="date_to" class="block text-sm font-semibold text-slate-300 mb-2">To Date</label>
                            <input 
                                type="date" 
                                id="date_to"
                                name="date_to" 
                                value="{{ $dateTo->format('Y-m-d') }}" 
                                class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                aria-label="End date"
                            >
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                Apply Filter
                            </button>
                            <a href="{{ route('analytics.index') }}" class="px-4 py-2.5 text-sm text-slate-300 hover:text-white bg-slate-800/60 hover:bg-slate-700/60 rounded-lg transition-all duration-300">
                                Reset
                            </a>
                        </div>
                    </div>
                    <!-- Quick Filters - Enhanced -->
                    <div class="flex flex-wrap gap-2">
                        @php
                            $today = \Carbon\Carbon::today();
                            $weekStart = \Carbon\Carbon::now()->startOfWeek();
                            $monthStart = \Carbon\Carbon::now()->startOfMonth();
                            $yearStart = \Carbon\Carbon::now()->startOfYear();
                        @endphp
                        <a href="{{ route('analytics.index', ['date_from' => $today->format('Y-m-d'), 'date_to' => $today->format('Y-m-d')]) }}" 
                           class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ $dateFrom->isSameDay($today) && $dateTo->isSameDay($today) ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                            Today
                        </a>
                        <a href="{{ route('analytics.index', ['date_from' => $weekStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" 
                           class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ $dateFrom->isSameDay($weekStart) ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                            This Week
                        </a>
                        <a href="{{ route('analytics.index', ['date_from' => $monthStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" 
                           class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ $dateFrom->isSameDay($monthStart) ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                            This Month
                        </a>
                        <a href="{{ route('analytics.index', ['date_from' => $yearStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" 
                           class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ $dateFrom->isSameDay($yearStart) ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                            This Year
                        </a>
                    </div>
                </form>
            </div>

            <!-- Today's Goal Card - Enhanced with Green -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-2xl blur opacity-25 group-hover:opacity-35 transition duration-500"></div>
                <div class="relative bg-gradient-to-br from-emerald-600/30 via-green-600/30 to-teal-600/30 border-2 border-emerald-500/50 rounded-2xl shadow-xl p-6 backdrop-blur-sm">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-teal-500/20 rounded-full blur-2xl -ml-16 -mb-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                                    <span class="text-xl">🎯</span>
                                    Today's Learning Goal
                                </h3>
                                <p class="text-base font-semibold text-emerald-200">{{ number_format($todayTotal, 1) }} / {{ number_format($dailyGoal, 1) }} hours</p>
                            </div>
                            <div class="text-6xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">
                                @if($todayTotal >= $dailyGoal) 🎉
                                @elseif($progressPercentage >= 75) 🚀
                                @elseif($progressPercentage >= 50) 💪
                                @else 📚
                                @endif
                            </div>
                        </div>
                        <div class="w-full h-6 bg-slate-800/80 rounded-full overflow-hidden mb-4 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-green-400 to-teal-400 transition-all duration-700 ease-out relative" 
                                 style="width: {{ min(100, $progressPercentage) }}%">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                                <div class="absolute inset-0 bg-gradient-to-b from-white/20 to-transparent"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            @if($remainingHours > 0)
                                <span class="text-sm font-semibold text-slate-300">
                                    <span class="text-white font-bold">{{ number_format($remainingHours, 1) }} hours</span> remaining
                                </span>
                            @else
                                <span class="text-sm font-bold text-emerald-300 bg-emerald-500/20 px-4 py-2 rounded-full border border-emerald-500/30">
                                    ✨ Goal achieved! 🎊
                                </span>
                            @endif
                            <span class="text-sm font-bold text-emerald-200 bg-emerald-500/20 px-4 py-2 rounded-full border border-emerald-500/30">{{ number_format($progressPercentage, 0) }}%</span>
                        </div>
                        @if(isset($encouragementMessage))
                        <div class="p-4 rounded-lg bg-slate-900/70 backdrop-blur-sm border border-emerald-500/30 shadow-lg">
                            <p class="text-sm text-slate-200 leading-relaxed font-medium">{{ $encouragementMessage['message'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Key Statistics Grid - Enhanced -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Filtered Total Hours -->
                <div class="group relative p-6 bg-gradient-to-br from-emerald-600/20 to-teal-600/20 border-2 border-emerald-500/30 rounded-xl shadow-lg hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-slate-300">Total Hours Studied</div>
                        <div class="text-4xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📊</div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-2">{{ number_format($filteredTotalHours, 1) }}</div>
                    <div class="text-xs font-medium text-emerald-300">
                        {{ $dateFrom->format('M j') }} - {{ $dateTo->format('M j, Y') }}
                    </div>
                </div>

                <!-- Average Daily Hours in Range -->
                <div class="group relative p-6 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border-2 border-indigo-500/30 rounded-xl shadow-lg hover:shadow-xl hover:border-indigo-500/50 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-slate-300">Avg Daily Hours</div>
                        <div class="text-4xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📈</div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-2">{{ number_format($averageDailyHoursInRange, 1) }}</div>
                    <div class="text-xs font-medium text-indigo-300">In selected period</div>
                </div>

                <!-- This Week Total -->
                <div class="group relative p-6 bg-gradient-to-br from-blue-600/20 to-cyan-600/20 border-2 border-blue-500/30 rounded-xl shadow-lg hover:shadow-xl hover:border-blue-500/50 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-slate-300">This Week</div>
                        <div class="text-4xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📅</div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-2">{{ number_format($weekTotal, 1) }}</div>
                    <div class="text-xs font-medium text-blue-300">hours</div>
                </div>

                <!-- Most Studied Skill -->
                <div class="group relative p-6 bg-gradient-to-br from-amber-600/20 to-orange-600/20 border-2 border-amber-500/30 rounded-xl shadow-lg hover:shadow-xl hover:border-amber-500/50 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-slate-300">Most Studied Skill</div>
                        <div class="text-4xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">⭐</div>
                    </div>
                    <div class="text-xl font-bold text-white mb-2">
                        {{ $mostStudiedSkill && $mostStudiedSkill->skill ? $mostStudiedSkill->skill->name : 'N/A' }}
                    </div>
                    @if($mostStudiedSkill && $mostStudiedSkill->skill)
                        <div class="text-xs font-medium text-amber-300">
                            {{ number_format($mostStudiedSkill->total_hours, 1) }} hours (all time)
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hours by Skill in Filtered Range - Enhanced -->
            @if($filteredBySkill->count() > 0)
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-5 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm">
                    <h3 class="text-xl font-bold text-white">Hours by Skill</h3>
                    <p class="text-sm text-slate-400 mt-1 font-medium">
                        Breakdown for {{ $dateFrom->format('M j') }} - {{ $dateTo->format('M j, Y') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($filteredBySkill as $skillData)
                            @php
                                $percentage = $filteredTotalHours > 0 ? ($skillData['hours'] / $filteredTotalHours) * 100 : 0;
                            @endphp
                            <div class="space-y-2 p-4 bg-slate-800/30 rounded-xl border border-slate-700/30 hover:bg-slate-800/50 transition-all duration-300">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white font-bold">{{ $skillData['skill_name'] }}</span>
                                    <span class="text-indigo-300 font-bold text-base">{{ number_format($skillData['hours'], 1) }} hrs</span>
                                </div>
                                <div class="w-full h-3 bg-slate-800/80 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-400 transition-all duration-700 ease-out relative" 
                                         style="width: {{ $percentage }}%">
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Today's Skills Breakdown - Enhanced -->
            @if($todayBySkill->count() > 0)
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-5 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm">
                    <h3 class="text-xl font-bold text-white">Today's Learning by Skill</h3>
                    <p class="text-sm text-slate-400 mt-1 font-medium">Breakdown of today's {{ number_format($todayTotal, 1) }} hours</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($todayBySkill as $skillData)
                            <div class="group relative p-5 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-xl border border-slate-700/50 hover:border-indigo-500/30 shadow-lg hover:shadow-xl transition-all duration-300 backdrop-blur-sm">
                                <div class="text-white font-bold mb-2 group-hover:text-indigo-300 transition-colors">{{ $skillData['skill_name'] }}</div>
                                <div class="text-3xl font-bold text-indigo-400 mb-1">{{ number_format($skillData['hours'], 1) }}</div>
                                <div class="text-xs font-medium text-slate-400">hours</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Chart Section - Enhanced -->
            @if(count($dateRangeDays) > 0)
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-5 border-b border-slate-800/50 bg-slate-900/50 backdrop-blur-sm">
                    <h3 class="text-xl font-bold text-white">Learning Hours Trend</h3>
                    <p class="text-sm text-slate-400 mt-1 font-medium">
                        {{ $dateFrom->format('M j') }} - {{ $dateTo->format('M j, Y') }}
                    </p>
                </div>
                <div class="p-6">
                    <canvas id="analyticsChart" style="height:240px" height="240"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(count($dateRangeDays) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('analyticsChart').getContext('2d');
            const chartData = @json($dateRangeDays);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(d => d.day),
                    datasets: [{
                        label: 'Learning Hours',
                        data: chartData.map(d => d.total_hours),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }, {
                        label: 'Daily Goal (8h)',
                        data: chartData.map(() => @json($dailyGoal)),
                        borderColor: 'rgba(34, 197, 94, 0.5)',
                        borderDash: [5, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#e2e8f0'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0',
                            bodyColor: '#e2e8f0',
                            borderColor: 'rgba(99, 102, 241, 0.5)',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#94a3b8',
                                callback: function(value) {
                                    return value + 'h';
                                }
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#94a3b8',
                                autoSkip: true,
                                maxTicksLimit: 15
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>
