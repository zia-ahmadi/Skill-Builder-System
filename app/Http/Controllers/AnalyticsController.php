<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tz = 'Asia/Kabul';
        $today = Carbon::today($tz);
        $weekStart = Carbon::now($tz)->startOfWeek();
        $weekEnd = Carbon::now($tz)->endOfWeek();
        $monthStart = Carbon::now($tz)->startOfMonth();
        $monthEnd = Carbon::now($tz)->endOfMonth();

        // Date range filtering
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from, $tz)->startOfDay()
            : $weekStart;
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to, $tz)->endOfDay()
            : $today;

        // Daily goal (8 hours per day)
        $dailyGoal = 8.0;

        // Today's learning hours by skill
        $todayBySkill = $user->studySessions()
            ->join('skills', 'study_sessions.skill_id', '=', 'skills.id')
            ->whereDate('study_sessions.session_date', $today)
            ->select('skills.name as skill_name', DB::raw('SUM(study_sessions.hours) as total_hours'))
            ->groupBy('skills.id', 'skills.name')
            ->orderBy('total_hours', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'skill_name' => $item->skill_name,
                    'hours' => (float) $item->total_hours,
                ];
            });

        $todayTotal = $todayBySkill->sum(fn($item) => $item['hours']);
        $remainingHours = max(0, $dailyGoal - $todayTotal);
        $progressPercentage = $dailyGoal > 0 ? min(100, ($todayTotal / $dailyGoal) * 100) : 0;

        // Get encouragement message
        $encouragementMessage = $this->getEncouragementMessage($todayTotal, $dailyGoal);

        // This week's learning hours by skill
        $weekBySkill = $user->studySessions()
            ->join('skills', 'study_sessions.skill_id', '=', 'skills.id')
            ->whereBetween('study_sessions.session_date', [$weekStart, $weekEnd])
            ->select('skills.name as skill_name', DB::raw('SUM(study_sessions.hours) as total_hours'))
            ->groupBy('skills.id', 'skills.name')
            ->orderBy('total_hours', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'skill_name' => $item->skill_name,
                    'hours' => (float) $item->total_hours,
                ];
            });

        $weekTotal = $weekBySkill->sum(fn($item) => $item['hours']);

        // This month's learning hours by skill
        $monthBySkill = $user->studySessions()
            ->join('skills', 'study_sessions.skill_id', '=', 'skills.id')
            ->whereBetween('study_sessions.session_date', [$monthStart, $monthEnd])
            ->select('skills.name as skill_name', DB::raw('SUM(study_sessions.hours) as total_hours'))
            ->groupBy('skills.id', 'skills.name')
            ->orderBy('total_hours', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'skill_name' => $item->skill_name,
                    'hours' => (float) $item->total_hours,
                ];
            });

        $monthTotal = $monthBySkill->sum(fn($item) => $item['hours']);

        // Filtered total hours studied (for date range)
        $filteredTotalHours = $user->studySessions()
            ->whereBetween('session_date', [$dateFrom, $dateTo])
            ->sum('hours');

        // Filtered hours by skill (for date range)
        $filteredBySkill = $user->studySessions()
            ->join('skills', 'study_sessions.skill_id', '=', 'skills.id')
            ->whereBetween('study_sessions.session_date', [$dateFrom, $dateTo])
            ->select('skills.name as skill_name', DB::raw('SUM(study_sessions.hours) as total_hours'))
            ->groupBy('skills.id', 'skills.name')
            ->orderBy('total_hours', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'skill_name' => $item->skill_name,
                    'hours' => (float) $item->total_hours,
                ];
            });

        // Daily breakdown for the selected date range
        $dateRangeDays = [];
        $currentDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);
        
        // Build daily breakdown for the selected date range (include zero-value days)
        while ($currentDate->lte($endDate)) {
            $dayTotal = $user->studySessions()
                ->whereDate('session_date', $currentDate)
                ->sum('hours');

            $dateRangeDays[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->format('M j, D'),
                'total_hours' => (float) $dayTotal,
                'goal_met' => $dayTotal >= $dailyGoal,
            ];

            $currentDate->addDay();
        }

        // Average daily hours for filtered range
        $daysInRange = max(1, $dateFrom->diffInDays($dateTo) + 1);
        $averageDailyHoursInRange = $daysInRange > 0 ? round($filteredTotalHours / $daysInRange, 2) : 0;

        // Most studied skill (all time)
        $mostStudiedSkill = $user->studySessions()
            ->select('skill_id', DB::raw('SUM(hours) as total_hours'))
            ->with('skill:id,name')
            ->groupBy('skill_id')
            ->orderBy('total_hours', 'desc')
            ->first();

        return view('analytics.index', compact(
            'todayBySkill',
            'todayTotal',
            'dailyGoal',
            'remainingHours',
            'progressPercentage',
            'encouragementMessage',
            'weekBySkill',
            'weekTotal',
            'monthBySkill',
            'monthTotal',
            'filteredTotalHours',
            'filteredBySkill',
            'dateFrom',
            'dateTo',
            'dateRangeDays',
            'averageDailyHoursInRange',
            'mostStudiedSkill'
        ));
    }

    private function getEncouragementMessage(float $hours, float $goal): array
    {
        if ($hours >= 10) {
            return [
                'message' => '🌟 Phenomenal! You\'ve achieved 10+ hours! Your dedication is extraordinary! Keep pushing beyond limits!',
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($hours > $goal) {
            $overGoal = $hours - $goal;
            return [
                'message' => "🎯 Amazing! You've exceeded your goal by {$overGoal} hours! Keep the momentum going! 🚀",
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($hours >= $goal) {
            return [
                'message' => '✨ Excellent work! You\'ve completed today\'s learning goal! Push yourself to go even further!',
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($hours >= ($goal * 0.75)) {
            return [
                'message' => '🚀 Great progress! You\'re more than 75% there. Keep going!',
                'type' => 'info',
                'color' => 'blue',
            ];
        } elseif ($hours >= ($goal * 0.5)) {
            return [
                'message' => '💪 You\'re halfway there! Keep pushing forward!',
                'type' => 'info',
                'color' => 'blue',
            ];
        } elseif ($hours > 0) {
            return [
                'message' => '🌱 Good start! Every hour counts. You can do this!',
                'type' => 'info',
                'color' => 'yellow',
            ];
        } else {
            return [
                'message' => '📚 Ready to start? Aim for 8 hours of learning today!',
                'type' => 'neutral',
                'color' => 'gray',
            ];
        }
    }

}

