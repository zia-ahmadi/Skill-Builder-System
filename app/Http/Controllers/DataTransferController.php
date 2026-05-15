<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\StudySession;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DataTransferController extends Controller
{
    private function buildUserCsvData(int $userId): array
    {
        $skills = Skill::where('user_id', $userId)->orderBy('id')->get();
        $topics = Topic::where('user_id', $userId)->orderBy('id')->get();
        $sessions = StudySession::where('user_id', $userId)->with('skill', 'topic')->orderBy('id')->get();

        $skillsCsv = collect([['id', 'name', 'goal_hours', 'deadline', 'description']])->merge($skills->map(function (Skill $s) {
            return [$s->id, $s->name, $s->goal_hours, $s->deadline, str_replace(["\r", "\n"], ' ', (string) $s->description)];
        }))->map(fn ($line) => implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $line)))->implode("\n");

        $topicsCsv = collect([['id', 'skill_id', 'name', 'description']])->merge($topics->map(function (Topic $t) {
            return [$t->id, $t->skill_id, $t->name, str_replace(["\r", "\n"], ' ', (string) $t->description)];
        }))->map(fn ($line) => implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $line)))->implode("\n");

        $sessionsCsv = collect([['id', 'session_date', 'skill_id', 'topic_id', 'hours', 'notes']])->merge($sessions->map(function (StudySession $s) {
            return [$s->id, $s->session_date->toDateString(), $s->skill_id, $s->topic_id, $s->hours, str_replace(["\r", "\n"], ' ', (string) $s->notes)];
        }))->map(fn ($line) => implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $line)))->implode("\n");

        return [$skillsCsv, $topicsCsv, $sessionsCsv, $skills->count(), $topics->count(), $sessions->count()];
    }

    private function backupMetaPath(int $userId): string
    {
        return storage_path('app/backup_meta_'.$userId.'.json');
    }

    private function readBackupMeta(int $userId): ?array
    {
        $path = $this->backupMetaPath($userId);
        if (!is_file($path)) {
            return null;
        }
        $json = @file_get_contents($path);
        if (!$json) {
            return null;
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return null;
        }
        return $data;
    }

    private function writeBackupMeta(int $userId, string $dir, int $skills, int $topics, int $sessions): void
    {
        $data = [
            'path' => $dir,
            'skills' => $skills,
            'topics' => $topics,
            'sessions' => $sessions,
            'time' => now()->toDateTimeString(),
        ];
        @file_put_contents($this->backupMetaPath($userId), json_encode($data));
    }

    public function autoBackupForUser(int $userId): void
    {
        $dir = 'D:/SBS_backups';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        if (!is_dir($dir)) {
            return;
        }
        [$skillsCsv, $topicsCsv, $sessionsCsv, $skillsCount, $topicsCount, $sessionsCount] = $this->buildUserCsvData($userId);
        @file_put_contents($dir.'/skills.csv', $skillsCsv);
        @file_put_contents($dir.'/topics.csv', $topicsCsv);
        @file_put_contents($dir.'/study_sessions.csv', $sessionsCsv);
        $this->writeBackupMeta($userId, $dir, $skillsCount, $topicsCount, $sessionsCount);
    }

    public function exportOptions(Request $request)
    {
        $userId = $request->user()->id;
        $defaultDir = 'D:/SBS_backups';
        $lastBackup = $this->readBackupMeta($userId);
        return view('data.backup_options', [
            'defaultDir' => $defaultDir,
            'lastBackup' => $lastBackup,
        ]);
    }

    public function exportToFolder(Request $request)
    {
        $dir = 'D:/SBS_backups';
        if (!is_dir($dir)) {
            return view('data.confirm_backup_dir', ['dir' => $dir]);
        }
        [$skillsCsv, $topicsCsv, $sessionsCsv, $skillsCount, $topicsCount, $sessionsCount] = $this->buildUserCsvData($request->user()->id);
        file_put_contents($dir.'/skills.csv', $skillsCsv);
        file_put_contents($dir.'/topics.csv', $topicsCsv);
        file_put_contents($dir.'/study_sessions.csv', $sessionsCsv);
        $this->writeBackupMeta($request->user()->id, $dir, $skillsCount, $topicsCount, $sessionsCount);
        return back()->with('status', 'Backup saved to '.$dir);
    }

    public function runExport(Request $request)
    {
        $userId = $request->user()->id;
        $mode = $request->input('mode', 'default');
        $dir = 'D:/SBS_backups';
        if ($mode === 'custom' && $request->filled('custom_path')) {
            $dir = $request->input('custom_path');
        }
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        if (!is_dir($dir)) {
            return back()->with('error', 'Unable to create backup folder.');
        }
        [$skillsCsv, $topicsCsv, $sessionsCsv, $skillsCount, $topicsCount, $sessionsCount] = $this->buildUserCsvData($userId);
        file_put_contents($dir.'/skills.csv', $skillsCsv);
        file_put_contents($dir.'/topics.csv', $topicsCsv);
        file_put_contents($dir.'/study_sessions.csv', $sessionsCsv);
        $this->writeBackupMeta($userId, $dir, $skillsCount, $topicsCount, $sessionsCount);
        return back()->with('status', 'Backup saved to '.$dir);
    }

    public function createBackupDir(Request $request)
    {
        $dir = 'D:/SBS_backups';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return redirect()->route('data.export');
    }
    public function exportCsv(Request $request)
    {
        $sessions = $request->user()->studySessions()
            ->with('skill', 'topic')
            ->orderBy('session_date')
            ->get();

        $csvLines = collect([
            ['session_date', 'skill', 'topic', 'hours', 'notes'],
        ])->merge($sessions->map(function (StudySession $s) {
            return [
                $s->session_date->toDateString(),
                $s->skill?->name,
                $s->topic?->name,
                $s->hours,
                str_replace(["\r", "\n"], ' ', (string) $s->notes),
            ];
        }));

        $content = $csvLines->map(fn ($line) => implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $line)))->implode("\n");

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="study_sessions.csv"');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,zip',
            'skill_id' => 'nullable|exists:skills,id',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $userId = $request->user()->id;
        $skillId = $request->input('skill_id');

        if ($extension === 'zip' && class_exists(ZipArchive::class)) {
            $zip = new ZipArchive();
            if ($zip->open($path) !== true) {
                return back()->with('error', 'Invalid zip file.');
            }
            $skillsCsv = $zip->getFromName('skills.csv') ?: $zip->getFromName('Skills.csv');
            $topicsCsv = $zip->getFromName('topics.csv') ?: $zip->getFromName('Topics.csv');
            $sessionsCsv = $zip->getFromName('study_sessions.csv') ?: $zip->getFromName('Study_Sessions.csv');
            $zip->close();

            $result = DB::transaction(function () use ($skillsCsv, $topicsCsv, $sessionsCsv, $userId, $skillId) {
                $skillIdMap = [];
                $topicIdMap = [];
                $skillsImported = 0;
                $topicsImported = 0;
                $sessionsImported = 0;

                if ($skillsCsv) {
                    $rows = str_getcsv($skillsCsv, "\n");
                    $header = isset($rows[0]) ? array_map('strtolower', str_getcsv($rows[0])) : [];
                    if ($header) {
                        for ($i = 1; $i < count($rows); $i++) {
                            $cols = str_getcsv($rows[$i]);
                            $data = [];
                            foreach ($header as $idx => $key) {
                                $data[$key] = $cols[$idx] ?? null;
                            }
                            $name = $data['name'] ?? null;
                            if (!$name) {
                                continue;
                            }
                            $skill = Skill::firstOrNew(
                                ['user_id' => $userId, 'name' => $name]
                            );
                            if (array_key_exists('goal_hours', $data) && is_numeric($data['goal_hours'])) {
                                $skill->goal_hours = $data['goal_hours'];
                            }
                            if (array_key_exists('deadline', $data)) {
                                $skill->deadline = $data['deadline'] ?: null;
                            }
                            if (array_key_exists('description', $data)) {
                                $skill->description = $data['description'];
                            }
                            $skill->save();
                            if (isset($data['id'])) {
                                $skillIdMap[$data['id']] = $skill->id;
                            }
                            $skillsImported++;
                        }
                    }
                }

                if ($topicsCsv) {
                    $rows = str_getcsv($topicsCsv, "\n");
                    $header = isset($rows[0]) ? array_map('strtolower', str_getcsv($rows[0])) : [];
                    if ($header) {
                        for ($i = 1; $i < count($rows); $i++) {
                            $cols = str_getcsv($rows[$i]);
                            $data = [];
                            foreach ($header as $idx => $key) {
                                $data[$key] = $cols[$idx] ?? null;
                            }
                            $name = $data['name'] ?? null;
                            if (!$name) {
                                continue;
                            }
                            $oldSkillId = $data['skill_id'] ?? null;
                            $newSkillId = $oldSkillId && isset($skillIdMap[$oldSkillId]) ? $skillIdMap[$oldSkillId] : null;
                            if (!$newSkillId) {
                                $skillNameFromTopics = $data['skill_name'] ?? ($data['skill'] ?? null);
                                if ($skillNameFromTopics) {
                                    $skill = Skill::firstOrNew(
                                        ['user_id' => $userId, 'name' => $skillNameFromTopics]
                                    );
                                    $skill->goal_hours = $skill->goal_hours ?? 0;
                                    $skill->save();
                                    $newSkillId = $skill->id;
                                }
                            }
                            if (!$newSkillId) {
                                continue;
                            }
                            $topic = Topic::firstOrNew(
                                ['user_id' => $userId, 'skill_id' => $newSkillId, 'name' => $name]
                            );
                            if (array_key_exists('description', $data)) {
                                $topic->description = $data['description'];
                            }
                            $topic->save();
                            if (isset($data['id'])) {
                                $topicIdMap[$data['id']] = $topic->id;
                            }
                            $topicsImported++;
                        }
                    }
                }

                if ($sessionsCsv) {
                    $rows = str_getcsv($sessionsCsv, "\n");
                    $header = isset($rows[0]) ? array_map('strtolower', str_getcsv($rows[0])) : [];
                    if ($header) {
                        for ($i = 1; $i < count($rows); $i++) {
                            $cols = str_getcsv($rows[$i]);
                            $data = [];
                            foreach ($header as $idx => $key) {
                                $data[$key] = $cols[$idx] ?? null;
                            }
                            $date = $data['session_date'] ?? now()->toDateString();
                            $hours = is_numeric($data['hours'] ?? null) ? $data['hours'] : 0;
                            $notes = $data['notes'] ?? null;
                            $skillIdSource = $data['skill_id'] ?? null;
                            $topicIdSource = $data['topic_id'] ?? null;
                            $skillNameSource = $data['skill'] ?? null;
                            $topicNameSource = $data['topic'] ?? null;

                            $targetSkillId = null;
                            if ($skillIdSource && isset($skillIdMap[$skillIdSource])) {
                                $targetSkillId = $skillIdMap[$skillIdSource];
                            } elseif ($skillNameSource) {
                                $skill = Skill::firstOrNew(
                                    ['user_id' => $userId, 'name' => $skillNameSource]
                                );
                                $skill->goal_hours = $skill->goal_hours ?? 0;
                                $skill->save();
                                $targetSkillId = $skill->id;
                            } elseif ($skillId) {
                                $targetSkillId = $skillId;
                            } else {
                                $skill = Skill::firstOrNew(
                                    ['user_id' => $userId, 'name' => 'General']
                                );
                                $skill->goal_hours = $skill->goal_hours ?? 0;
                                $skill->save();
                                $targetSkillId = $skill->id;
                            }

                            $targetTopicId = null;
                            if ($topicIdSource && isset($topicIdMap[$topicIdSource])) {
                                $targetTopicId = $topicIdMap[$topicIdSource];
                            } elseif ($topicNameSource) {
                                $topic = Topic::firstOrNew(
                                    ['user_id' => $userId, 'skill_id' => $targetSkillId, 'name' => $topicNameSource]
                                );
                                $topic->save();
                                $targetTopicId = $topic->id;
                            }

                            StudySession::create([
                                'user_id' => $userId,
                                'skill_id' => $targetSkillId,
                                'topic_id' => $targetTopicId,
                                'session_date' => $date,
                                'hours' => $hours,
                                'notes' => $notes,
                            ]);
                            $sessionsImported++;
                        }
                    }
                }

                return [
                    'skillsCsv' => $skillsCsv,
                    'topicsCsv' => $topicsCsv,
                    'sessionsCsv' => $sessionsCsv,
                    'skillsImported' => $skillsImported,
                    'topicsImported' => $topicsImported,
                    'sessionsImported' => $sessionsImported,
                ];
            });

            $summary = [];
            if ($result['skillsCsv']) {
                $summary[] = $result['skillsImported'].' skills';
            }
            if ($result['topicsCsv']) {
                $summary[] = $result['topicsImported'].' topics';
            }
            if ($result['sessionsCsv']) {
                $summary[] = $result['sessionsImported'].' sessions';
            }
            $msg = $summary ? ('Imported '.implode(', ', $summary).'.') : 'No CSV files found in zip.';
            if ($summary) {
                app(self::class)->autoBackupForUser($userId);
            }
            return back()->with('status', $msg);
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'Empty CSV.');
        }
        $header = array_map('strtolower', $header);

        $imported = 0;
        if (in_array('name', $header) && !in_array('session_date', $header) && !in_array('skill_id', $header) && !in_array('skill_name', $header) && !in_array('skill', $header)) {
            $nameIdx = array_search('name', $header);
            $goalIdx = array_search('goal_hours', $header);
            $deadlineIdx = array_search('deadline', $header);
            $descIdx = array_search('description', $header);
            while (($row = fgetcsv($handle)) !== false) {
                $name = $row[$nameIdx] ?? null;
                if (!$name) continue;
                $skill = Skill::firstOrNew(
                    ['user_id' => $userId, 'name' => $name]
                );
                if (isset($goalIdx) && is_numeric($row[$goalIdx] ?? null)) {
                    $skill->goal_hours = $row[$goalIdx];
                }
                if (isset($deadlineIdx)) {
                    $skill->deadline = $row[$deadlineIdx] ?? null;
                }
                if (isset($descIdx)) {
                    $skill->description = $row[$descIdx] ?? null;
                }
                $skill->save();
                $imported++;
            }
            fclose($handle);
            if ($imported > 0) {
                app(self::class)->autoBackupForUser($userId);
            }
            return back()->with('status', "Imported {$imported} skills.");
        }

        if (in_array('name', $header) && !in_array('session_date', $header) && (in_array('skill_id', $header) || in_array('skill_name', $header) || in_array('skill', $header))) {
            $nameIdx = array_search('name', $header);
            $skillIdIdx = in_array('skill_id', $header) ? array_search('skill_id', $header) : null;
            $skillNameIdx = null;
            if (in_array('skill_name', $header)) {
                $skillNameIdx = array_search('skill_name', $header);
            } elseif (in_array('skill', $header)) {
                $skillNameIdx = array_search('skill', $header);
            }
            $descIdx = array_search('description', $header);
            while (($row = fgetcsv($handle)) !== false) {
                $name = $row[$nameIdx] ?? null;
                if (!$name) continue;
                $targetSkillId = null;
                if ($skillIdIdx !== null) {
                    $candidateId = $row[$skillIdIdx] ?? null;
                    if ($candidateId && Skill::where('user_id', $userId)->where('id', $candidateId)->exists()) {
                        $targetSkillId = $candidateId;
                    }
                }
                if (!$targetSkillId && $skillNameIdx !== null) {
                    $skillName = $row[$skillNameIdx] ?? null;
                    if ($skillName) {
                        $skill = Skill::firstOrNew(
                            ['user_id' => $userId, 'name' => $skillName]
                        );
                        $skill->goal_hours = $skill->goal_hours ?? 0;
                        $skill->save();
                        $targetSkillId = $skill->id;
                    }
                }
                if (!$targetSkillId) {
                    continue;
                }
                $topic = Topic::firstOrNew(
                    ['user_id' => $userId, 'skill_id' => $targetSkillId, 'name' => $name]
                );
                if (isset($descIdx)) {
                    $topic->description = $row[$descIdx] ?? null;
                }
                $topic->save();
                $imported++;
            }
            fclose($handle);
            if ($imported > 0) {
                app(self::class)->autoBackupForUser($userId);
            }
            return back()->with('status', "Imported {$imported} topics.");
        }

        $created = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $row = array_pad($row, max(count($header), 5), null);
            if (in_array('session_date', $header)) {
                $date = $row[array_search('session_date', $header)] ?? now()->toDateString();
                $hours = $row[array_search('hours', $header)] ?? 0;
                $notes = $row[array_search('notes', $header)] ?? null;
                $skillName = in_array('skill', $header) ? ($row[array_search('skill', $header)] ?? null) : null;
                $topicName = in_array('topic', $header) ? ($row[array_search('topic', $header)] ?? null) : null;
                $skillIdSource = in_array('skill_id', $header) ? ($row[array_search('skill_id', $header)] ?? null) : null;
                $topicIdSource = in_array('topic_id', $header) ? ($row[array_search('topic_id', $header)] ?? null) : null;

                $targetSkillId = null;
                if ($skillIdSource && Skill::where('user_id', $userId)->where('id', $skillIdSource)->exists()) {
                    $targetSkillId = $skillIdSource;
                } elseif ($skillName) {
                    $targetSkillId = Skill::firstOrCreate(
                        ['user_id' => $userId, 'name' => $skillName],
                        ['goal_hours' => 0]
                    )->id;
                } elseif ($skillId) {
                    $targetSkillId = $skillId;
                } else {
                    $targetSkillId = Skill::firstOrCreate(
                        ['user_id' => $userId, 'name' => 'General'],
                        ['goal_hours' => 0]
                    )->id;
                }

                $targetTopicId = null;
                if ($topicIdSource && Topic::where('user_id', $userId)->where('id', $topicIdSource)->exists()) {
                    $targetTopicId = $topicIdSource;
                } elseif ($topicName) {
                    $targetTopicId = Topic::firstOrCreate(
                        ['user_id' => $userId, 'skill_id' => $targetSkillId, 'name' => $topicName]
                    )->id;
                }

                StudySession::create([
                    'user_id' => $userId,
                    'skill_id' => $targetSkillId,
                    'topic_id' => $targetTopicId,
                    'session_date' => $date,
                    'hours' => is_numeric($hours) ? $hours : 0,
                    'notes' => $notes,
                ]);
                $created++;
            } else {
                [$date, $skillName, $topicName, $hours, $notes] = $row;
                $targetSkillId = $skillId ?: Skill::firstOrCreate(
                    ['user_id' => $userId, 'name' => $skillName ?: 'General'],
                    ['goal_hours' => 0]
                )->id;
                $topicId = null;
                if ($topicName) {
                    $topicId = Topic::firstOrCreate(
                        ['user_id' => $userId, 'skill_id' => $targetSkillId, 'name' => $topicName]
                    )->id;
                }
                StudySession::create([
                    'user_id' => $userId,
                    'skill_id' => $targetSkillId,
                    'topic_id' => $topicId,
                    'session_date' => $date ?: now()->toDateString(),
                    'hours' => is_numeric($hours) ? $hours : 0,
                    'notes' => $notes,
                ]);
                $created++;
            }
        }
        fclose($handle);
        if ($created > 0) {
            app(self::class)->autoBackupForUser($userId);
        }
        return back()->with('status', "Imported {$created} sessions.");
    }

    public function backup(Request $request)
    {
        $user = $request->user();
        $data = [
            'skills' => $user->skills()->get()->toArray(),
            'topics' => $user->topics()->get()->toArray(),
            'study_sessions' => $user->studySessions()->get()->toArray(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);

        if (class_exists(ZipArchive::class)) {
            $zipPath = storage_path('app/backup.zip');

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return back()->with('error', 'Unable to create zip.');
            }

            $zip->addFromString('backup.json', $json);
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="backup.json"');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup' => 'required|file',
        ]);

        $file = $request->file('backup');
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'zip' && class_exists(ZipArchive::class)) {
            $zip = new ZipArchive();

            if ($zip->open($path) !== true) {
                return back()->with('error', 'Invalid zip file.');
            }

            $json = $zip->getFromName('backup.json');
            $zip->close();
        } else {
            $json = file_get_contents($path);
        }

        if (!$json) {
            return back()->with('error', 'backup data is missing.');
        }

        $payload = json_decode($json, true);
        if (!$payload) {
            return back()->with('error', 'Unable to read backup data.');
        }

        $userId = $request->user()->id;

        DB::transaction(function () use ($payload, $userId) {

            Skill::where('user_id', $userId)->delete();
            Topic::where('user_id', $userId)->delete();
            StudySession::where('user_id', $userId)->delete();

            $skillIdMap = [];
            foreach ($payload['skills'] ?? [] as $skill) {
                $new = Skill::create([
                    'user_id' => $userId,
                    'name' => $skill['name'],
                    'goal_hours' => $skill['goal_hours'] ?? 0,
                    'deadline' => $skill['deadline'] ?? null,
                    'description' => $skill['description'] ?? null,
                ]);
                $skillIdMap[$skill['id']] = $new->id;
            }

            $topicIdMap = [];
            foreach ($payload['topics'] ?? [] as $topic) {
                $new = Topic::create([
                    'user_id' => $userId,
                    'skill_id' => $skillIdMap[$topic['skill_id']] ?? null,
                    'name' => $topic['name'],
                    'description' => $topic['description'] ?? null,
                ]);
                $topicIdMap[$topic['id']] = $new->id;
            }

            foreach ($payload['study_sessions'] ?? [] as $session) {
                StudySession::create([
                    'user_id' => $userId,
                    'skill_id' => $skillIdMap[$session['skill_id']] ?? null,
                    'topic_id' => $session['topic_id'] ? ($topicIdMap[$session['topic_id']] ?? null) : null,
                    'session_date' => $session['session_date'] ?? now()->toDateString(),
                    'hours' => $session['hours'] ?? 0,
                    'notes' => $session['notes'] ?? null,
                ]);
            }
        });

        app(self::class)->autoBackupForUser($userId);

        return back()->with('status', 'Backup restored.');
    }
}

