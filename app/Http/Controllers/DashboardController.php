<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        
        $skills = $user->skills()
            ->withCount(['studySessions as total_hours' => function ($q) {
                $q->select(\DB::raw('COALESCE(SUM(hours),0)'));
            }])
            ->orderBy('deadline')
            ->get();

        // Goal settings
        $dailyGoal = 8.0;
        $weeklyGoal = 50.0;
        $monthlyGoal = 200.0;

        // Date ranges
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Daily progress
        $todayTotal = $user->studySessions()
            ->whereDate('session_date', $today)
            ->sum('hours');
        $dailyProgress = $dailyGoal > 0 ? min(100, ($todayTotal / $dailyGoal) * 100) : 0;
        $dailyRemaining = max(0, $dailyGoal - $todayTotal);

        // Weekly progress
        $weekTotal = $user->studySessions()
            ->whereBetween('session_date', [$weekStart, $weekEnd])
            ->sum('hours');
        $weeklyProgress = $weeklyGoal > 0 ? min(100, ($weekTotal / $weeklyGoal) * 100) : 0;
        $weeklyRemaining = max(0, $weeklyGoal - $weekTotal);

        // Monthly progress
        $monthTotal = $user->studySessions()
            ->whereBetween('session_date', [$monthStart, $monthEnd])
            ->sum('hours');
        $monthlyProgress = $monthlyGoal > 0 ? min(100, ($monthTotal / $monthlyGoal) * 100) : 0;
        $monthlyRemaining = max(0, $monthlyGoal - $monthTotal);

        // Lifetime goal (10,000 hours to mastery - inspired by Robert Greene/Malcolm Gladwell)
        $lifetimeGoal = 10000.0;
        $lifetimeTotal = $user->studySessions()->sum('hours');
        $lifetimeProgress = $lifetimeGoal > 0 ? min(100, ($lifetimeTotal / $lifetimeGoal) * 100) : 0;
        $lifetimeRemaining = max(0, $lifetimeGoal - $lifetimeTotal);

        // Calculate estimated time to mastery
        $averageDailyHours = $weekTotal > 0 ? $weekTotal / min(7, Carbon::now()->diffInDays($weekStart) + 1) : 0;
        $estimatedDaysToMastery = $averageDailyHours > 0 && $lifetimeRemaining > 0 
            ? ceil($lifetimeRemaining / $averageDailyHours) 
            : null;
        $estimatedDateToMastery = $estimatedDaysToMastery 
            ? Carbon::now()->addDays($estimatedDaysToMastery) 
            : null;

        // Additional statistics
        $totalSkills = $skills->count();
        $activeSkills = $skills->filter(function($skill) {
            return ($skill->total_hours ?? 0) > 0;
        })->count();

        // Today's sessions count
        $todaySessionsCount = $user->studySessions()
            ->whereDate('session_date', $today)
            ->count();

        // This week sessions count
        $weekSessionsCount = $user->studySessions()
            ->whereBetween('session_date', [$weekStart, $weekEnd])
            ->count();

        // Get encouragement messages
        $dailyEncouragementMessage = $this->getEncouragementMessage($todayTotal, $dailyGoal);
        $weeklyEncouragementMessage = $this->getEncouragementMessage($weekTotal, $weeklyGoal);
        $monthlyEncouragementMessage = $this->getEncouragementMessage($monthTotal, $monthlyGoal);
        $lifetimeEncouragementMessage = $this->getLifetimeEncouragementMessage($lifetimeTotal, $lifetimeGoal, $lifetimeProgress);

        return view('dashboard', compact(
            'skills',
            'dailyGoal',
            'todayTotal',
            'dailyProgress',
            'dailyRemaining',
            'dailyEncouragementMessage',
            'weeklyGoal',
            'weekTotal',
            'weeklyProgress',
            'weeklyRemaining',
            'weeklyEncouragementMessage',
            'monthlyGoal',
            'monthTotal',
            'monthlyProgress',
            'monthlyRemaining',
            'monthlyEncouragementMessage',
            'lifetimeGoal',
            'lifetimeTotal',
            'lifetimeProgress',
            'lifetimeRemaining',
            'lifetimeEncouragementMessage',
            'estimatedDaysToMastery',
            'estimatedDateToMastery',
            'averageDailyHours',
            'totalSkills',
            'activeSkills',
            'todaySessionsCount',
            'weekSessionsCount'
        ));
    }

    private function getEncouragementMessage(float $hours, float $goal): array
    {
        if ($hours >= ($goal * 1.25)) {
            $overGoal = $hours - $goal;
            return [
                'message' => "🌟 Phenomenal! You've exceeded your goal by " . number_format($overGoal, 1) . " hours! Your dedication is extraordinary!",
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($hours >= $goal) {
            return [
                'message' => '✨ Excellent work! You\'ve completed your goal! Push yourself to go even further!',
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
        } elseif ($hours >= ($goal * 0.25)) {
            return [
                'message' => '🌱 Good start! You\'re making progress. Keep the momentum!',
                'type' => 'info',
                'color' => 'yellow',
            ];
        } elseif ($hours > 0) {
            return [
                'message' => '📚 You\'ve started! Every hour counts. Keep going!',
                'type' => 'info',
                'color' => 'yellow',
            ];
        } else {
            return [
                'message' => 'Ready to start? Begin your learning journey today!',
                'type' => 'neutral',
                'color' => 'gray',
            ];
        }
    }

    private function getLifetimeEncouragementMessage(float $totalHours, float $goal, float $progress): array
    {
        if ($progress >= 100) {
            return [
                'message' => '👑 MASTER ACHIEVED! You\'ve reached the 10,000 hour milestone! You are now a true master of your craft! This is an extraordinary achievement! 🎉',
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($progress >= 90) {
            return [
                'message' => '🌟 You\'re in the final stretch! Over 90% complete! Just a few more hours to mastery. Keep pushing - you\'re almost there! 💪',
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($progress >= 75) {
            return [
                'message' => '✨ Incredible progress! You\'re more than 75% to mastery! You\'ve put in over 7,500 hours - that\'s dedication! Keep going! 🚀',
                'type' => 'success',
                'color' => 'green',
            ];
        } elseif ($progress >= 50) {
            return [
                'message' => '💎 Halfway to mastery! You\'ve logged over 5,000 hours - you\'re halfway to becoming a true expert! This is remarkable! ⭐',
                'type' => 'info',
                'color' => 'blue',
            ];
        } elseif ($progress >= 25) {
            $hoursLogged = number_format($totalHours, 0);
            return [
                'message' => "🔥 Great foundation! You've logged {$hoursLogged} hours. You're building a solid base for mastery. Keep the momentum! 📚",
                'type' => 'info',
                'color' => 'blue',
            ];
        } elseif ($progress >= 10) {
            $hoursLogged = number_format($totalHours, 0);
            $remaining = number_format($goal - $totalHours, 0);
            return [
                'message' => "🌱 Excellent start! You've completed {$hoursLogged} hours. Every hour brings you closer to mastery. {$remaining} hours to go! 💪",
                'type' => 'info',
                'color' => 'yellow',
            ];
        } elseif ($totalHours > 0) {
            $hoursLogged = number_format($totalHours, 1);
            $remaining = number_format($goal - $totalHours, 0);
            return [
                'message' => "📚 You've started your journey! {$hoursLogged} hours logged. The path to mastery begins with a single hour. {$remaining} hours to go! 🌟",
                'type' => 'info',
                'color' => 'yellow',
            ];
        } else {
            return [
                'message' => '🎯 Ready to begin? The journey to mastery starts with your first hour! Robert Greene says mastery requires 10,000 hours. Let\'s begin! 💪',
                'type' => 'neutral',
                'color' => 'gray',
            ];
        }
    }

}

