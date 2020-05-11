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
        return ($this->attributes['minutes_attended'] ?? null) !== null
            ? $this->attributes['minutes_attended']
            : ($this->programEdition->schedules->sum('working_minutes') ?: null);
    }

    public function getHoursAttendedAttribute()
    {
        return ($this->attributes['hours_attended'] ?? null) !== null
            ?  $this->attributes['hours_attended']
            :  round($this->getMinutesAttendedAttribute() / 60, 2);
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
