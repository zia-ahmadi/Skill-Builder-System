<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'goal_hours',
        'deadline',
        'description',
        'banner_path',
    ];

    protected $casts = [
        'goal_hours' => 'float',
        'deadline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }

    public function totalHours(): float
    {
        return (float) $this->studySessions()->sum('hours');
    }

    public function remainingHours(): float
    {
        return max(0, $this->goal_hours - $this->totalHours());
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_path ? asset('storage/'.$this->banner_path) : null;
    }
}

