<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTransferController;
use App\Http\Controllers\LearnedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->middleware('verified')->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/learned', [LearnedController::class, 'index'])->name('learned.index');

    Route::resource('skills', SkillController::class);

    Route::post('skills/{skill}/topics', [TopicController::class, 'store'])->name('skills.topics.store');
    Route::delete('skills/{skill}/topics/{topic}', [TopicController::class, 'destroy'])->name('skills.topics.destroy');
    Route::put('skills/{skill}/topics/{topic}', [TopicController::class, 'update'])->name('skills.topics.update');

    Route::post('skills/{skill}/sessions', [StudySessionController::class, 'store'])->name('skills.sessions.store');
    Route::delete('skills/{skill}/sessions/{studySession}', [StudySessionController::class, 'destroy'])->name('skills.sessions.destroy');
    Route::put('skills/{skill}/sessions/{studySession}', [StudySessionController::class, 'update'])->name('skills.sessions.update');

    Route::get('data/export', [DataTransferController::class, 'exportOptions'])->name('data.export');
    Route::post('data/export/run', [DataTransferController::class, 'runExport'])->name('data.export.run');
    Route::post('data/export/create-dir', [DataTransferController::class, 'createBackupDir'])->name('data.export.create_dir');
    Route::get('data/export/sessions', [DataTransferController::class, 'exportCsv'])->name('data.export.sessions');
    Route::post('data/import', [DataTransferController::class, 'importCsv'])->name('data.import');
    Route::get('data/backup', [DataTransferController::class, 'backup'])->name('data.backup');
    Route::post('data/restore', [DataTransferController::class, 'restore'])->name('data.restore');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
