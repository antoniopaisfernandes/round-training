<?php

namespace App;

use App\Student;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function programEditions()
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

    public function executedCostsInYear($year)
    {
        return $this->with([
                'programEditions' => fn($query) => $query->whereYear('starts_at', $year)
            ])
            ->get()
            ->pluck('programEditions')
            ->flatten()
            ->map
            ->splited_costs
            ->flatten()
            ->filter(fn(self $company) => $company->id == $this->id)
            ->sum('cost');
    }
}
