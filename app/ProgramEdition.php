<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
                    ->withTimestamps();
    }

    public function getFullNameAttribute()
    {
        return $this->program->name . ' - ' . $this->name;
    }

    public function scopeStatus($query, $status)
    {
        if ($status == 'active') {
            $query->where('ends_at', '>=', today());
        } elseif ($status == 'ended') {
            $query->where('ends_at', '<', today());
        } elseif ($status == 'future') {
            $query->where('starts_at', '>', today());
        }
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
            DB::transaction(function () use ($students) {
                $students->each->enroll($this);
            });
        }

        else {
            throw new InvalidArgumentException("Parameter must be either a collection or a student");
        }
    }
}
