<?php

namespace App;

use App\ProgramEdition;
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
    protected $with = [
        'company',
    ];

    public function enrollments()
    {
        return $this->belongsToMany(ProgramEdition::class, 'enrollments')
            ->as('enrollments')
            ->withPivot('company_id')
            ->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function enroll(ProgramEdition $programEdition)
    {
        $this->enrollments()->sync([
            $programEdition->id => [
                'company_id' => $this->current_company_id,
            ],
        ]);
    }
}
