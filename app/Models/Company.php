<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function programEditions(): HasMany
    {
        return $this->hasMany(ProgramEdition::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(CompanyYearlyBudget::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function executedCostsInYear($year)
    {
        return $this->with([
            'programEditions' => fn ($query) => $query->whereYear('starts_at', $year),
        ])
            ->get()
            ->pluck('programEditions')
            ->flatten()
            ->map
            ->splited_costs
            ->flatten()
            ->filter(fn (self $company) => $company->id == $this->id)
            ->sum('cost');
    }
}
