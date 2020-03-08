<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramEdition extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [
        'program',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function schedules()
    {
        return $this->hasMany(ProgramEditionSchedules::class);
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withTimestamps();
    }
}
