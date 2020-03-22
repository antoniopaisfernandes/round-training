<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'phone' => 'string',
        'current_company_id' => 'int',
    ];
    public $rgpdFields = [
        'citizen_id',
        'citizen_id_validity',
    ];

    public function enrolments()
    {
        return $this->belongsToMany(ProgramEdition::class, 'enrollments')
            ->withTimestamps();
    }
}
