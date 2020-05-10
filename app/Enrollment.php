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
        return $this->attributes['minutes_attended'] !== null
            ? $this->attributes['minutes_attended']
            : ($this->programEdition->schedules->sum('working_minutes') ?: null);
    }
}
