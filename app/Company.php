<?php

namespace App;

use App\Student;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function programsEditions()
    {
        return $this->hasMany(ProgramEdition::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function budgets()
    {
        return $this->hasMany(CompanyYearlyBudget::class);
    }
}
