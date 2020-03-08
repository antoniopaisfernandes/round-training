<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function enrolments()
    {
        return $this->belongsToMany(ProgramEdition::class, 'enrollments')
            ->withTimestamps();
    }
}
