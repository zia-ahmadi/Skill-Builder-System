@php
    $totalHours = $skill->studySessions->sum('hours');
    $pct = $skill->goal_hours > 0 ? min(100, round(($totalHours / $skill->goal_hours) * 100)) : 0;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">{{ $skill->name }}</h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ number_format($totalHours, 1) }} / {{ number_format($skill->goal_hours, 1) }} hrs ({{ $pct }}%)
                    @if($skill->deadline)
                        @php
                            $deadlineDate = \Illuminate\Support\Carbon::parse($skill->deadline)->startOfDay();
                            $today = now()->startOfDay();
                            $isPast = $deadlineDate->isPast();
                            $daysDifference = $isPast ? 0 : (int) $today->diffInDays($deadlineDate, false);
                        @endphp
                        • 
                        @if($isPast)
                            <span class="text-red-400">Deadline Finished</span>
                        @else
                            @if($daysDifference == 0)
                                Due Today
                            @elseif($daysDifference == 1)
                                Due in 1 day
                            @else
                                Due in {{ $daysDifference }} days
                            @endif
                        @endif
                    @endif
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('skills.edit', $skill) }}" class="px-5 py-2.5 text-sm text-indigo-300 hover:text-white border border-indigo-600/40 bg-indigo-600/20 hover:bg-indigo-600/40 rounded-lg font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">Edit</a>
                <a href="{{ route('skills.index') }}" class="px-5 py-2.5 text-sm text-gray-300 hover:text-white border border-slate-700/60 bg-slate-800/40 hover:bg-slate-700/60 rounded-lg font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if($skill->banner_url)
                <div class="relative group overflow-hidden rounded-2xl border-2 border-slate-700/50 bg-slate-900/50 shadow-2xl">
                    <img src="{{ $skill->banner_url }}" alt="{{ $skill->name }} banner" class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 to-transparent"></div>
                </div>
            @endif

            <!-- Progress Stats Cards - Enhanced -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="group relative p-6 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border-2 border-indigo-500/30 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 backdrop-blur-sm">
                    <div class="text-sm font-semibold text-slate-300 mb-2 uppercase tracking-wide">Progress</div>
                    <div class="text-4xl font-bold text-white mb-3">{{ $pct }}%</div>
                    <div class="h-3 bg-slate-800/80 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-400 transition-all duration-700 ease-out relative progress-bar" 
                             style="width: 0%" data-progress="{{ $pct }}">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                        </div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-green-600/20 to-emerald-600/20 border-2 border-green-500/30 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 backdrop-blur-sm">
                    <div class="text-sm font-semibold text-slate-300 mb-2 uppercase tracking-wide">Remaining Hours</div>
                    <div class="text-4xl font-bold text-white mb-2">{{ number_format($remainingHours, 1) }}</div>
                    <div class="text-xs font-medium text-green-300">Goal: {{ number_format($skill->goal_hours,1) }} hrs</div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-amber-600/20 to-orange-600/20 border-2 border-amber-500/30 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 backdrop-blur-sm">
                    <div class="text-sm font-semibold text-slate-300 mb-2 uppercase tracking-wide">Daily Target</div>
                    <div class="text-4xl font-bold text-white mb-2">
                        {{ $dailyTarget ? number_format($dailyTarget, 2) : '—' }} <span class="text-lg font-normal">hrs/day</span>
                    </div>
                    <div class="text-xs font-medium text-amber-300">
                        {{ $daysLeft ? $daysLeft . ' days left' : 'No deadline set' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 shadow-2xl backdrop-blur-sm">
                    <h3 class="text-xl font-bold text-white mb-4">Hours by Day</h3>
                    <canvas id="dailyChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 shadow-2xl backdrop-blur-sm">
                    <h3 class="text-xl font-bold text-white mb-4">Hours by Month</h3>
                    <canvas id="monthlyChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 space-y-5 lg:col-span-2 shadow-2xl backdrop-blur-sm">
                    <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                        <h3 class="text-xl font-bold text-white">Study Sessions</h3>
                        <span class="text-sm font-semibold text-slate-400 bg-slate-800/60 px-3 py-1.5 rounded-full">{{ $skill->studySessions->count() }} entries</span>
                    </div>
                    <form method="POST" action="{{ route('skills.sessions.store', $skill) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-800/30 rounded-xl border border-slate-700/30">
                        @csrf
                        <input type="date" name="session_date" value="{{ now()->toDateString() }}" class="rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <select name="topic_id" class="rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">No topic</option>
                            @foreach($skill->topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.1" min="0.1" name="hours" placeholder="Hours" class="rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <input type="text" name="notes" placeholder="Notes" class="rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 md:col-span-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <button class="md:col-span-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Add Session</button>
                    </form>
                    <div class="divide-y divide-slate-800/50">
                        @forelse($skill->studySessions as $session)
                            <div class="py-4 flex items-start justify-between gap-4 hover:bg-slate-800/30 px-3 -mx-3 rounded-lg transition-colors group">
                                <div class="flex-1">
                                    <div class="text-white font-bold mb-1">
                                        {{ $session->session_date->toDateString() }} • <span class="text-indigo-300">{{ number_format($session->hours, 1) }} hrs</span>
                                    </div>
                                    <div class="text-sm text-slate-400 font-medium">
                                        @if($session->topic) <span class="text-indigo-300">Topic:</span> {{ $session->topic->name }} • @endif
                                        {{ $session->notes }}
                                    </div>
                                </div>
                                <form action="{{ route('skills.sessions.destroy', [$skill, $session]) }}" method="POST" onsubmit="return confirm('Delete session?')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300 text-sm font-semibold transition-colors">Delete</button>
                                </form>
                            </div>
                        @empty
                            <div class="py-10 text-gray-400 text-center">
                                <div class="text-5xl mb-3">📚</div>
                                <p class="font-medium">No sessions yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 space-y-4 shadow-2xl backdrop-blur-sm">
                        <div class="flex items-center justify-between border-b border-slate-700/50 pb-4">
                            <h3 class="text-xl font-bold text-white">Topics</h3>
                            <span class="text-sm font-semibold text-slate-400 bg-slate-800/60 px-3 py-1.5 rounded-full">{{ $skill->topics->count() }}</span>
                        </div>
                        <form method="POST" action="{{ route('skills.topics.store', $skill) }}" class="space-y-3 p-4 bg-slate-800/30 rounded-xl border border-slate-700/30">
                            @csrf
                            <input name="name" placeholder="Topic name" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <textarea name="description" rows="2" placeholder="Description (optional)" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"></textarea>
                            <button class="w-full px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Add Topic</button>
                        </form>
                        <div class="divide-y divide-slate-800/50">
                            @forelse($skill->topics as $topic)
                                <div class="py-3 flex items-start justify-between hover:bg-slate-800/30 px-3 -mx-3 rounded-lg transition-colors group">
                                    <div class="flex-1">
                                        <div class="text-white font-bold mb-1">{{ $topic->name }}</div>
                                        @if($topic->description)
                                            <div class="text-sm text-slate-400 italic">{{ $topic->description }}</div>
                                        @endif
                                    </div>
                                    <form action="{{ route('skills.topics.destroy', [$skill, $topic]) }}" method="POST" onsubmit="return confirm('Delete topic?')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-300 text-sm font-semibold transition-colors">Delete</button>
                                    </form>
                                </div>
                            @empty
                                <div class="py-8 text-center text-gray-400">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p class="font-medium">No topics yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl p-6 space-y-4 shadow-2xl backdrop-blur-sm">
                        <h3 class="text-xl font-bold text-white border-b border-slate-700/50 pb-4">Data Management</h3>
                        <a href="{{ route('data.export.sessions') }}" class="block px-5 py-3 bg-slate-800/60 hover:bg-slate-700/60 border border-slate-700/50 rounded-lg text-sm text-white text-center font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">Export Sessions CSV</a>
                        <form method="POST" action="{{ route('data.import') }}" enctype="multipart/form-data" class="space-y-3 p-4 bg-slate-800/30 rounded-xl border border-slate-700/30">
                            @csrf
                            <input type="file" name="file" accept=".csv,text/csv,.zip" class="w-full text-sm text-gray-300 bg-slate-800/60 border border-slate-700/50 rounded-lg px-4 py-2.5">
                            <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                            <button class="w-full px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Import CSV to this skill</button>
                        </form>
                        <a href="{{ route('data.backup') }}" class="block px-5 py-3 bg-slate-800/60 hover:bg-slate-700/60 border border-slate-700/50 rounded-lg text-sm text-white text-center font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">Backup</a>
                        <form method="POST" action="{{ route('data.restore') }}" enctype="multipart/form-data" class="space-y-3 p-4 bg-slate-800/30 rounded-xl border border-slate-700/30">
                            @csrf
                            <input type="file" name="backup" accept=".zip,.json" class="w-full text-sm text-gray-300 bg-slate-800/60 border border-slate-700/50 rounded-lg px-4 py-2.5">
                            <button class="w-full px-5 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 rounded-lg text-white text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">Restore from file</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="dailyLabelsJson" value='{{ $dailyHours->keys()->toJson() }}'>
    <input type="hidden" id="dailyDataJson" value='{{ $dailyHours->values()->toJson() }}'>
    <input type="hidden" id="monthlyLabelsJson" value='{{ $monthlyHours->keys()->toJson() }}'>
    <input type="hidden" id="monthlyDataJson" value='{{ $monthlyHours->values()->toJson() }}'>
    <script>
        document.querySelectorAll('.progress-bar[data-progress]').forEach(function (el) {
            var value = parseFloat(el.getAttribute('data-progress'));
            if (!isNaN(value)) {
                el.style.width = value + '%';
            }
        });

        const dailyCtx = document.getElementById('dailyChart');
        const monthlyCtx = document.getElementById('monthlyChart');

        const dailyLabels = JSON.parse(document.getElementById('dailyLabelsJson').value);
        const dailyData = JSON.parse(document.getElementById('dailyDataJson').value);

        const monthlyLabels = JSON.parse(document.getElementById('monthlyLabelsJson').value);
        const monthlyData = JSON.parse(document.getElementById('monthlyDataJson').value);

        if (dailyCtx) {
            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Hours',
                        data: dailyData,
                        backgroundColor: '#6366f1',
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { ticks: { color: '#cbd5e1' } }, y: { ticks: { color: '#cbd5e1' } } }
                }
            });
        }

        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Hours',
                        data: monthlyData,
                        borderColor: '#22d3ee',
                        backgroundColor: '#22d3ee44',
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { x: { ticks: { color: '#cbd5e1' } }, y: { ticks: { color: '#cbd5e1' } } }
                }
            });
        }
    </script>
</x-app-layout>

