<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudySessionRequest;
use App\Http\Requests\UpdateStudySessionRequest;
use App\Models\Skill;
use App\Models\StudySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudySessionController extends Controller
{
    public function store(StoreStudySessionRequest $request, Skill $skill)
    {
        $this->authorizeSkill($skill);

        $session = $skill->studySessions()->create([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($session->user_id);

        return back()->with('status', 'Study session logged.');
    }

    public function update(UpdateStudySessionRequest $request, Skill $skill, StudySession $studySession)
    {
        $this->authorizeSkill($skill);
        $this->authorizeSession($studySession);

        $studySession->update($request->validated());

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($studySession->user_id);

        return back()->with('status', 'Session updated.');
    }

    public function destroy(Skill $skill, StudySession $studySession)
    {
        $this->authorizeSkill($skill);
        $this->authorizeSession($studySession);

        $userId = $studySession->user_id;
        $studySession->delete();

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($userId);

        return back()->with('status', 'Session removed.');
    }

    private function authorizeSkill(Skill $skill): void
    {
        $userId = Auth::id();
        abort_unless($userId && $skill->user_id === $userId, 403);
    }

    private function authorizeSession(StudySession $session): void
    {
        $userId = Auth::id();
        abort_unless($userId && $session->user_id === $userId, 403);
    }
}

