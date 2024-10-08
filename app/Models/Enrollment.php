<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'program_edition_id' => 'int',
        'student_id' => 'int',
        'company_id' => 'int',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function programEdition(): BelongsTo
    {
        return $this->belongsTo(ProgramEdition::class);
    }

    public function getDueToEvaluateAttribute()
    {
        return $this->global_evaluation === null;
    }

    public function getMinutesAttendedAttribute()
    {
        return ($this->attributes['minutes_attended'] ?? null) !== null
            ? $this->attributes['minutes_attended']
            : ($this->programEdition->schedules->sum('working_minutes') ?: null);
    }

    public function getHoursAttendedAttribute()
    {
        return ($this->attributes['hours_attended'] ?? null) !== null
            ? $this->attributes['hours_attended']
            : round($this->getMinutesAttendedAttribute() / 60, 2);
    }

    public function setHoursAttendedAttribute($hours)
    {
        if ($hours === null) {
            $this->attributes['minutes_attended'] = null;
        } else {
            $this->attributes['minutes_attended'] = (int) ($hours * 60);
        }
    }

    public function scopeStatus(Builder $query, string $status)
    {
        $query->whereHas('programEdition', fn ($q) => $q->status($status));
    }
}
