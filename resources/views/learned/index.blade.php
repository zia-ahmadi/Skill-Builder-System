<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-100 leading-tight">
                    Learning History
                </h2>
                <p class="text-sm text-gray-400 mt-1">All your recorded learning sessions</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Statistics Cards - Enhanced -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-indigo-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Total Sessions</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($totalSessions) }}</div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📝</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-purple-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Total Hours</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($totalHours, 1) }}</div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">⏱️</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-blue-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Skills Learned</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($uniqueSkills) }}</div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">🎯</div>
                    </div>
                </div>
                <div class="group relative p-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-xl shadow-lg hover:shadow-xl hover:border-emerald-500/30 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Days Active</div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($daysActive) }}</div>
                        </div>
                        <div class="text-5xl transform transition-transform group-hover:scale-110 group-hover:rotate-12">📅</div>
                    </div>
                </div>
            </div>

            <!-- Filters and Sorting - Enhanced -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl p-6 backdrop-blur-sm">
                <form method="GET" action="{{ route('learned.index') }}" class="space-y-5">
                    <!-- Search Bar -->
                    <div>
                        <label for="search" class="block text-sm font-semibold text-slate-300 mb-2">Search</label>
                        <input 
                            type="text" 
                            id="search"
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by skill name, topic, or notes..."
                            class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            aria-label="Search learning sessions"
                        >
                    </div>

                    <!-- Quick Date Filters - Enhanced -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Quick Filters</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $today = \Carbon\Carbon::today();
                                $weekStart = \Carbon\Carbon::now()->startOfWeek();
                                $monthStart = \Carbon\Carbon::now()->startOfMonth();
                                $yearStart = \Carbon\Carbon::now()->startOfYear();
                            @endphp
                            <a href="{{ route('learned.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $today->format('Y-m-d'), 'date_to' => $today->format('Y-m-d')])) }}" 
                               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ request('date_from') == $today->format('Y-m-d') && request('date_to') == $today->format('Y-m-d') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                                Today
                            </a>
                            <a href="{{ route('learned.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $weekStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')])) }}" 
                               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ request('date_from') == $weekStart->format('Y-m-d') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                                This Week
                            </a>
                            <a href="{{ route('learned.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $monthStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')])) }}" 
                               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ request('date_from') == $monthStart->format('Y-m-d') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                                This Month
                            </a>
                            <a href="{{ route('learned.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => $yearStart->format('Y-m-d'), 'date_to' => \Carbon\Carbon::now()->format('Y-m-d')])) }}" 
                               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 {{ request('date_from') == $yearStart->format('Y-m-d') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105' }}">
                                This Year
                            </a>
                            <a href="{{ route('learned.index', request()->except(['date_from', 'date_to'])) }}" 
                               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-300 bg-slate-800/60 text-slate-300 hover:bg-slate-700/60 hover:scale-105">
                                All Time
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div>
                            <label for="skill_id" class="block text-sm font-semibold text-slate-300 mb-2">Filter by Skill</label>
                            <select id="skill_id" name="skill_id" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Filter by skill">
                                <option value="">All Skills</option>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date_from" class="block text-sm font-semibold text-slate-300 mb-2">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Start date">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-semibold text-slate-300 mb-2">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="End date">
                        </div>
                        <div>
                            <label for="sort_by" class="block text-sm font-semibold text-slate-300 mb-2">Sort By</label>
                            <select id="sort_by" name="sort_by" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Sort order">
                                <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>Date (Newest)</option>
                                <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>Date (Oldest)</option>
                                <option value="hours_desc" {{ request('sort_by') == 'hours_desc' ? 'selected' : '' }}>Hours (High to Low)</option>
                                <option value="hours_asc" {{ request('sort_by') == 'hours_asc' ? 'selected' : '' }}>Hours (Low to High)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-700/50">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['skill_id', 'date_from', 'date_to', 'sort_by', 'search']))
                            <a href="{{ route('learned.index') }}" class="px-5 py-2.5 text-sm text-slate-300 hover:text-white bg-slate-800/60 hover:bg-slate-700/60 rounded-lg font-semibold transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Learning Sessions List - Enhanced -->
            <div class="bg-gradient-to-br from-slate-900/90 to-slate-800/90 border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-6 py-5 border-b border-slate-800/50 flex justify-between items-center bg-slate-900/50 backdrop-blur-sm">
                    <div>
                        <h3 class="text-xl font-bold text-white">Learning Sessions</h3>
                        <p class="text-sm text-slate-400 mt-1 font-medium">
                            @if(request()->hasAny(['skill_id', 'date_from', 'date_to', 'search']))
                                Showing {{ $sessions->total() }} {{ Str::plural('result', $sessions->total()) }}
                            @else
                                Complete history of your learning journey
                            @endif
                        </p>
                    </div>
                    @if($sessions->count() > 0)
                        <div class="flex items-center gap-3">
                            <div class="text-sm font-semibold text-slate-400 bg-slate-800/60 px-3 py-1.5 rounded-full">
                                {{ $sessions->firstItem() }} - {{ $sessions->lastItem() }} of {{ $sessions->total() }}
                            </div>
                            <button 
                                onclick="window.print()" 
                                class="px-4 py-2 text-xs font-semibold bg-slate-800/60 hover:bg-slate-700/60 text-slate-300 rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2 backdrop-blur-sm border border-slate-700/50"
                                aria-label="Print learning history"
                            >
                                <span>🖨️</span> Print
                            </button>
                        </div>
                    @endif
                </div>

                @if($sessions->count() > 0)
                    <div class="divide-y divide-slate-800/30">
                        @foreach($sessionsByDate as $date => $dateSessions)
                            @php
                                $dateCarbon = \Carbon\Carbon::parse($date);
                                $dayTotal = $dateSessions->sum('hours');
                            @endphp
                            <div class="p-6 hover:bg-slate-800/20 transition-colors duration-300">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-600/30 to-purple-600/30 border border-indigo-500/40 flex items-center justify-center shadow-lg backdrop-blur-sm">
                                            <span class="text-xl font-bold text-indigo-200">{{ $dateCarbon->format('d') }}</span>
                                        </div>
                                        <div>
                                            <div class="text-white font-bold text-lg mb-1">
                                                {{ $dateCarbon->format('l, F j, Y') }}
                                            </div>
                                            <div class="text-sm font-semibold text-slate-400">
                                                {{ $dateSessions->count() }} session{{ $dateSessions->count() !== 1 ? 's' : '' }} • 
                                                <span class="font-bold text-indigo-300 text-base">{{ number_format($dayTotal, 1) }} hours</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if($dayTotal >= 8)
                                        <span class="px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-green-500/30 to-emerald-500/30 text-green-300 border border-green-500/40 shadow-lg backdrop-blur-sm">
                                            Goal Met ✓
                                        </span>
                                    @endif
                                </div>
                                <div class="space-y-3 ml-20">
                                    @foreach($dateSessions as $session)
                                        <div class="flex items-start justify-between p-5 bg-gradient-to-br from-slate-800/40 to-slate-900/40 rounded-xl border border-slate-700/40 hover:border-indigo-500/30 hover:bg-slate-800/60 transition-all duration-300 group shadow-lg backdrop-blur-sm">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <a href="{{ route('skills.show', $session->skill) }}" class="text-white font-bold text-lg hover:text-indigo-300 transition-colors">
                                                        {{ $session->skill->name }}
                                                    </a>
                                                    @if($session->topic)
                                                        <span class="text-slate-500 text-sm">•</span>
                                                        <span class="text-slate-300 text-sm font-semibold bg-slate-700/40 px-2 py-1 rounded-lg border border-slate-600/40">{{ $session->topic->name }}</span>
                                                    @endif
                                                </div>
                                                @if($session->notes)
                                                    <p class="text-sm text-slate-400 mb-3 italic leading-relaxed">{{ Str::limit($session->notes, 150) }}</p>
                                                @endif
                                                <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                                                    <span>Logged: {{ $session->created_at->format('g:i A') }}</span>
                                                    @if($session->created_at->format('Y-m-d') !== $session->session_date->format('Y-m-d'))
                                                        <span>•</span>
                                                        <span>Session Date: {{ $session->session_date->format('M j, Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-6 flex items-center gap-4">
                                                <div class="text-right">
                                                    <div class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                                                        {{ number_format($session->hours, 1) }}
                                                    </div>
                                                    <div class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wide">hours</div>
                                                </div>
                                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                    <button 
                                                        onclick="openEditModal({{ $session->id }}, '{{ $session->session_date->format('Y-m-d') }}', {{ $session->hours }}, {{ json_encode($session->notes ?? '') }}, {{ $session->topic_id ?? 'null' }}, {{ $session->skill_id }})"
                                                        class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-indigo-600/30 to-purple-600/30 hover:from-indigo-600/50 hover:to-purple-600/50 text-indigo-300 rounded-lg border border-indigo-500/40 transition-all duration-300 hover:scale-105 shadow-lg backdrop-blur-sm"
                                                        aria-label="Edit session"
                                                    >
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('skills.sessions.destroy', [$session->skill, $session]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button 
                                                            type="submit"
                                                            class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-red-600/30 to-rose-600/30 hover:from-red-600/50 hover:to-rose-600/50 text-red-300 rounded-lg border border-red-500/40 transition-all duration-300 hover:scale-105 shadow-lg backdrop-blur-sm"
                                                            aria-label="Delete session"
                                                        >
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination - Enhanced -->
                    <div class="px-6 py-5 border-t border-slate-800/50 bg-slate-900/30 backdrop-blur-sm">
                        {{ $sessions->links() }}
                    </div>
                @else
                    <div class="px-6 py-20 text-center">
                        <div class="text-7xl mb-6 transform animate-pulse">📚</div>
                        @if(request()->hasAny(['skill_id', 'date_from', 'date_to', 'search']))
                            <h3 class="text-xl font-bold text-white mb-3">No sessions found</h3>
                            <p class="text-slate-400 mb-8 font-medium">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('learned.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                Clear Filters
                            </a>
                        @else
                            <h3 class="text-xl font-bold text-white mb-3">No learning sessions yet</h3>
                            <p class="text-slate-400 mb-8 font-medium">Start logging your study sessions to build your learning history!</p>
                            <a href="{{ route('skills.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                Go to Skills
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Session Modal - Enhanced -->
    <x-modal name="edit-session-modal">
        <div class="p-8 bg-gradient-to-br from-slate-900/95 to-slate-800/95 border border-slate-700/50 rounded-2xl shadow-2xl text-white backdrop-blur-sm">
            <h2 class="text-2xl font-bold text-white mb-6">Edit Learning Session</h2>
            <form id="edit-session-form" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_session_date" class="block text-sm font-semibold text-slate-300 mb-2">Session Date</label>
                    <input type="date" id="edit_session_date" name="session_date" required class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Session date">
                </div>
                <div>
                    <label for="edit_hours" class="block text-sm font-semibold text-slate-300 mb-2">Hours</label>
                    <input type="number" id="edit_hours" name="hours" step="0.1" min="0.1" required class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Hours">
                </div>
                <div>
                    <label for="edit_topic_id" class="block text-sm font-semibold text-slate-300 mb-2">Topic (Optional)</label>
                    <select id="edit_topic_id" name="topic_id" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" aria-label="Topic">
                        <option value="">No topic</option>
                        <!-- Topics will be populated dynamically -->
                    </select>
                </div>
                <div>
                    <label for="edit_notes" class="block text-sm font-semibold text-slate-300 mb-2">Notes (Optional)</label>
                    <textarea id="edit_notes" name="notes" rows="4" class="w-full rounded-lg bg-slate-800/80 border-slate-700/60 text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none" aria-label="Notes" placeholder="Add notes about this session..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-5 border-t border-slate-700/50">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-sm font-semibold text-slate-300 hover:text-white border border-slate-700/60 bg-slate-800/40 hover:bg-slate-700/60 rounded-lg transition-all duration-300 hover:scale-105 backdrop-blur-sm">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                        Update Session
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        // Store skills and topics data for modal
        const skillsData = @json($skills->mapWithKeys(function($skill) {
            return [$skill->id => $skill->topics->map(function($topic) {
                return ['id' => $topic->id, 'name' => $topic->name];
            })];
        }));

        function openEditModal(sessionId, sessionDate, hours, notes, topicId, skillId) {
            const form = document.getElementById('edit-session-form');
            form.action = `/skills/${skillId}/sessions/${sessionId}`;
            document.getElementById('edit_session_date').value = sessionDate;
            document.getElementById('edit_hours').value = hours;
            document.getElementById('edit_notes').value = notes || '';
            
            // Populate topics dropdown for the skill
            const topicSelect = document.getElementById('edit_topic_id');
            topicSelect.innerHTML = '<option value="">No topic</option>';
            
            if (skillsData[skillId]) {
                skillsData[skillId].forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = topic.name;
                    if (topicId && topic.id == topicId) {
                        option.selected = true;
                    }
                    topicSelect.appendChild(option);
                });
            }
            
            // Show modal using Alpine.js event
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-session-modal' }));
        }

        function closeEditModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-session-modal' }));
        }
    </script>

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            nav,
            header,
            button,
            form,
            .group-hover\:opacity-100 {
                display: none !important;
            }
            .bg-slate-900\/70,
            .bg-slate-800\/50 {
                background: white !important;
                border: 1px solid #e5e7eb !important;
                color: black !important;
            }
            .text-white,
            .text-slate-300,
            .text-slate-400 {
                color: black !important;
            }
            a {
                color: black !important;
                text-decoration: underline;
            }
        }
    </style>
</x-app-layout>

