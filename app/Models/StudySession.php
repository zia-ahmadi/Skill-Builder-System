<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_id',
        'topic_id',
        'session_date',
        'hours',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'date',
        'hours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

