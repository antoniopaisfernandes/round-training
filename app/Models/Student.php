<?php

namespace App\Models;

use App\Exceptions\CannotEnrollStudentException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory,
        SoftDeletes;

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

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledProgramEditions(): BelongsToMany
    {
        return $this->belongsToMany(ProgramEdition::class, 'enrollments')
            ->as('enrollments')
            ->withPivot('company_id')
            ->withTimestamps();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function enroll(ProgramEdition $programEdition, array $attributes = [])
    {
        throw_if(
            ($attributes['minutes_attended'] ?? 0) > $programEdition->schedules->sum('working_minutes'),
            new CannotEnrollStudentException('You are trying to enroll for more minutes than the scheduled lectures')
        );

        return $this->enrollments()->firstOrCreate(array_merge(
            [
                'program_edition_id' => $programEdition->id,
                'company_id' => $this->current_company_id,
            ],
            $attributes
        ));
    }

    public function scopeNotEnrolled(Builder $query, ...$programEditionIds)
    {
        $query->whereNotIn('id', static function ($query) use ($programEditionIds) {
            $query->select('student_id')
                ->from('enrollments')
                ->whereIn('enrollments.program_edition_id', Arr::wrap($programEditionIds));
        });
    }

    public function scopeCanBeEnrolled(Builder $query)
    {
        $enrollableProgramEditionsIds = ProgramEdition::setEagerLoads([])
            ->status('enrollable')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $query->notEnrolled(...$enrollableProgramEditionsIds);
    }

    public function scopeCanBeEnrolledBy(Builder $query, User $user)
    {
        $query->canBeEnrolled()
            ->where(function ($q) use ($user) {
                $q->where(DB::raw(true), DB::raw($user->can('store_enrollment') ?: 0))
                    ->orWhere('leader_id', $user->id)
                    ->orWhereHas('company', function ($company) use ($user) {
                        $company->where('coordinator_id', $user->id);
                    });
            });
    }
}
