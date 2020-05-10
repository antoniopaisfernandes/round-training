<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [];
    protected $casts = [
        'program_edition_id' => 'int',
        'student_id' => 'int',
        'company_id' => 'int',
    ];
    protected $appends = [
        'hours_attended',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function programEdition()
    {
        return $this->belongsTo(ProgramEdition::class);
    }

    public function getDueToEvaluateAttribute()
    {
        return $this->global_evaluation === null;
    }

    public function getMinutesAttendedAttribute()
    {
        return $this->attributes['minutes_attended'] !== null
            ? $this->attributes['minutes_attended']
            : ($this->programEdition->schedules->sum('working_minutes') ?: null);
    }

    public function getHoursAttendedAttribute()
    {
        if (!isset($this->attributes['minutes_attended']) || $this->attributes['minutes_attended'] === null) {
            return null;
        }

        return round($this->attributes['minutes_attended'] / 60, 2);
    }

    public function setHoursAttendedAttribute($hours)
    {
        if ($hours === null) {
            $this->attributes['minutes_attended'] = null;
        } else {
            $this->attributes['minutes_attended'] = (int) ($hours * 60);
        }
    }
}
