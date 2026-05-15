<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $skills = $request->user()->skills()->withCount(['studySessions as total_hours' => function ($q) {
            $q->select(DB::raw('COALESCE(SUM(hours),0)'));
        }])->with('topics')->latest()->get();

        return view('skills.index', [
            'skills' => $skills,
        ]);
    }

    public function create()
    {
        return view('skills.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'goal_hours' => 'required|numeric|min:0.1',
            'deadline' => ['nullable', 'date', 'after:today'],
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'goal_hours.min' => 'Goal hours must be greater than 0.',
            'deadline.after' => 'Deadline must be in the future.',
        ]);

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('skills', 'public');
        }

        $skill = $request->user()->skills()->create($data);

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($skill->user_id);

        return redirect()->route('skills.index')->with('status', 'Skill created.');
    }

    public function show(Skill $skill)
    {
        $this->authorizeSkill($skill);

        $skill->load(['topics', 'studySessions' => function ($q) {
            $q->latest('session_date');
        }]);

        $daily = $skill->studySessions
            ->groupBy(fn ($s) => $s->session_date->toDateString())
            ->map->sum('hours');

        $monthly = $skill->studySessions
            ->groupBy(fn ($s) => $s->session_date->format('Y-m'))
            ->map->sum('hours');

        $remainingHours = max(0, $skill->goal_hours - $skill->studySessions->sum('hours'));
        $daysLeft = $skill->deadline ? Carbon::now()->diffInDays($skill->deadline, false) : null;
        $dailyTarget = $daysLeft && $daysLeft > 0 ? round($remainingHours / $daysLeft, 2) : null;

        return view('skills.show', [
            'skill' => $skill,
            'dailyHours' => $daily,
            'monthlyHours' => $monthly,
            'remainingHours' => $remainingHours,
            'dailyTarget' => $dailyTarget,
            'daysLeft' => $daysLeft,
        ]);
    }

    public function edit(Skill $skill)
    {
        $this->authorizeSkill($skill);
        return view('skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $this->authorizeSkill($skill);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'goal_hours' => 'required|numeric|min:0.1',
            'deadline' => ['nullable', 'date', 'after:today'],
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'goal_hours.min' => 'Goal hours must be greater than 0.',
            'deadline.after' => 'Deadline must be in the future.',
        ]);

        if ($request->hasFile('banner')) {
            if ($skill->banner_path) {
                Storage::disk('public')->delete($skill->banner_path);
            }
            $data['banner_path'] = $request->file('banner')->store('skills', 'public');
        }

        $skill->update($data);

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($skill->user_id);

        return redirect()->route('skills.show', $skill)->with('status', 'Skill updated.');
    }

    public function destroy(Skill $skill)
    {
        $this->authorizeSkill($skill);
        $userId = $skill->user_id;
        $skill->delete();
        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($userId);

        return redirect()->route('skills.index')->with('status', 'Skill removed.');
    }

    private function authorizeSkill(Skill $skill): void
    {
        $userId = Auth::id();
        abort_unless($userId && $skill->user_id === $userId, 403);
    }
}

