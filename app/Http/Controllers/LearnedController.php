<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LearnedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = $user->studySessions()->with(['skill:id,name', 'topic:id,name']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('skill', function ($skillQuery) use ($search) {
                    $skillQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('topic', function ($topicQuery) use ($search) {
                    $topicQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filter by skill
        if ($request->filled('skill_id')) {
            $query->where('skill_id', $request->skill_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('session_date', '<=', $request->date_to);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'date_desc'); // date_desc, date_asc, hours_desc, hours_asc
        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('session_date', 'asc')->orderBy('created_at', 'asc');
                break;
            case 'hours_desc':
                $query->orderBy('hours', 'desc')->orderBy('session_date', 'desc');
                break;
            case 'hours_asc':
                $query->orderBy('hours', 'asc')->orderBy('session_date', 'desc');
                break;
            default: // date_desc
                $query->orderBy('session_date', 'desc')->orderBy('created_at', 'desc');
                break;
        }
        
        $sessions = $query->paginate(20)->withQueryString();
        
        // Get skills with topics for filter dropdown and modal
        $skills = $user->skills()->with('topics')->orderBy('name')->get();

        // Calculate total statistics
        $totalHours = $user->studySessions()->sum('hours');
        $totalSessions = $user->studySessions()->count();
        $uniqueSkills = $user->studySessions()->distinct('skill_id')->count('skill_id');

        // Get date range stats
        $firstSession = $user->studySessions()->orderBy('session_date')->first();
        $daysActive = $firstSession 
            ? Carbon::parse($firstSession->session_date)->diffInDays(Carbon::now()) + 1 
            : 0;

        // Group sessions by date for better organization
        $sessionsByDate = $sessions->groupBy(function ($session) {
            return $session->session_date->format('Y-m-d');
        });

        return view('learned.index', compact(
            'sessions',
            'sessionsByDate',
            'totalHours',
            'totalSessions',
            'uniqueSkills',
            'daysActive',
            'skills'
        ));
    }
}

