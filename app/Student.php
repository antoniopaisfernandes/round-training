<?php

namespace App;

use App\ProgramEdition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'phone' => 'string',
        'current_company_id' => 'int',
        'leader_id' => 'int',
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
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledProgramEditions()
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

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function enroll(ProgramEdition $programEdition)
    {
        return $this->enrollments()->firstOrCreate([
            'program_edition_id' => $programEdition->id,
            'company_id' => $this->current_company_id,
        ]);
    }

    public function scopeNotEnrolled(Builder $query, ...$programEditionIds)
    {
        $query->whereNotIn('id', static function ($query) use ($programEditionIds) {
            $query->select('student_id')
                ->from('enrollments')
                ->whereIn('enrollments.program_edition_id', Arr::wrap($programEditionIds));
        });
    }
}
