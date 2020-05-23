<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProgramEdition extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [
        'program',
        'manager',
        'schedules',
    ];
    protected $casts = [
        'cost' => 'double',
        'program_id' => 'int',
        'company_id' => 'int',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];
    protected $withCount = [
        'students',
    ];
    protected $appends = [
        'full_name',
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
        return $this->hasMany(ProgramEditionSchedule::class)
                    ->orderBy('starts_at');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot([
                        'id',
                        'company_id',
                        'minutes_attended',
                        'hours_attended',
                        'global_evaluation',
                        'evaluation_comments',
                        'program_should_be_repeated',
                        'should_be_repeated_in_months',
                    ])
                    ->withTimestamps();
    }

    public function getFullNameAttribute()
    {
        return $this->program->name . ' - ' . $this->name;
    }

    /**
     * Get the splitted costs by company weighted by the number of students
     * If there are no students enrolled, the cost is assumed by the buying
     * company (defined by the company_id field in this model)
     *
     * @return Collection<Company>
     */
    public function getSplitedCostsAttribute()
    {
        if (($students = $this->students)->isEmpty()) {
            return collect([$this->company->setAttribute('cost', $this->cost)]);
        }

        return $students->groupBy(function ($student) {
                return $student->pivot->company_id ?: $this->id;
            })
            ->map(function ($companyStudents, $company_id) use ($students) {
                return Company::find($company_id)
                    ->setAttribute('cost', round($companyStudents->count() / $students->count() * $this->cost, 2));
            })
            ->values();
    }

    /**
     * The companies eligible to assume the cost of the program edition are
     * all in the enrollments with the one that purchased from the supplier
     *
     * @return Collection
     */
    public function getEligiblePurchasingCompanyIdsAttribute()
    {
        return $this->enrollments->pluck('company_id')->push($this->company_id)->unique()->values();
    }

    public function scopeStatus(Builder $query, string $status)
    {
        if ($status == 'active') {
            $query->where('starts_at', '<=', today())
                ->where('ends_at', '>=', today());
        } elseif ($status == 'ended') {
            $query->where('ends_at', '<', today());
        } elseif ($status == 'future') {
            $query->where('starts_at', '>', today());
        }
    }

    public function scopeDueToEvaluate(Builder $query, string $operator = '<', Carbon $date = null)
    {
        $query->where('evaluation_notification_date', $operator, $date ?: today())
            ->whereHas('enrollments', fn($builder) => $builder->whereNull('global_evaluation'));
    }

    public function emailsToNotify($scope = 'dueToEvaluate') : Collection
    {
        return $this->enrollments
            ->filter
            ->{$scope}
            ->map
            ->student
            ->map
            ->leader
            ->pluck('email')
            ->unique();
    }

    public function getEmailsToNotifyOfDueEvaluationAttribute()
    {
        return $this->emailsToNotify('dueToEvaluate');
    }

    /**
     * Enroll students in current program edition
     *
     * @param Collection|Student $students
     * @return void
     * @throws \Exception
     */
    public function enroll($students)
    {
        if ($students instanceof Student) {
            $students->enroll($this);
        }

        elseif ($students instanceof Collection) {
            DB::transaction(fn () => $students->each->enroll($this));
        }

        else {
            throw new InvalidArgumentException("Parameter must be either a collection or a student");
        }
    }
}
