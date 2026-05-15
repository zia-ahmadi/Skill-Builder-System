<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function store(Request $request, Skill $skill)
    {
        $this->authorizeSkill($skill);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $topic = $skill->topics()->create([
            'user_id' => $request->user()->id,
            ...$data,
        ]);

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($topic->user_id);

        return back()->with('status', 'Topic added.');
    }

    public function update(Request $request, Skill $skill, Topic $topic)
    {
        $this->authorizeSkill($skill);
        $this->authorizeTopic($topic);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $topic->update($data);

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($topic->user_id);

        return back()->with('status', 'Topic updated.');
    }

    public function destroy(Skill $skill, Topic $topic)
    {
        $this->authorizeSkill($skill);
        $this->authorizeTopic($topic);

        $userId = $topic->user_id;
        $topic->delete();

        app(\App\Http\Controllers\DataTransferController::class)->autoBackupForUser($userId);

        return back()->with('status', 'Topic deleted.');
    }

    private function authorizeSkill(Skill $skill): void
    {
        abort_unless($skill->user_id === auth()->id(), 403);
    }

    private function authorizeTopic(Topic $topic): void
    {
        abort_unless($topic->user_id === auth()->id(), 403);
    }
}

