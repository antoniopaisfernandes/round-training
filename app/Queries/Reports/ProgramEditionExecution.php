<?php

namespace App\Queries\Reports;

use App\Models\Company;
use App\Models\ProgramEdition;
use Illuminate\Support\Collection;

class ProgramEditionExecution
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $programEditions = ProgramEdition::with('enrollments')
            ->where('starts_at', '>=', $this->filters['year'] . '-01-01')
            ->where('starts_at', '<=', $this->filters['year'] . '-12-31')
            ->get();

        $purchasingMatrix = $programEditions->map
            ->eligible_purchasing_company_ids
            ->flatten()
            ->unique()
            ->values()
            ->flip()
            ->map(fn() => 0)
            ->toArray();

        $headers = $this->headers(array_keys($purchasingMatrix));
        $data = $programEditions->map(function (ProgramEdition $programEdition) use ($purchasingMatrix) {
            $programEdition->splited_costs->each(function (Company $company) use (&$purchasingMatrix) {
                $purchasingMatrix[$company->id] = $company->cost;
            });

            return [
                $programEdition->full_name,
                $programEdition->students_count,
                $programEdition->supplier,
                $programEdition->schedules->sum('working_hours'),
                $programEdition->starts_at,
                $programEdition->ends_at,
                $programEdition->location ?: '',
                ...$purchasingMatrix,
            ];
        });

        return $headers->merge($data);
    }

    public function headers($companyIds)
    {
        $companies = Company::whereIn('id', $companyIds)->with([
            'budgets' => fn($query) => $query->where('year', $this->filters['year'])
        ])->get();

        return collect([
            [
                ...array_fill(0, 7, ''),
                ...$companies->pluck('short_name')->flatten()->toArray(),
            ],
            [
                ...array_fill(0, 6, ''),
                __('app.budget'),
                ...$companies->pluck('budgets')
                    ->map(fn ($budgets) => $budgets->first())
                    ->map(fn ($budget) => optional($budget)->budget)
                    ->toArray(),
            ],
            [
                ...array_fill(0, 6, ''),
                __('app.execution'),
                ...$companies->pluck('budgets')
                    ->map(fn ($budgets) => $budgets->first())
                    ->map(fn ($budget) => optional($budget)->execution)
                    ->toArray(),
            ],
            [
                __('app.training'),
                __('app.students'),
                __('app.supplier'),
                __('app.time'),
                __('app.start_date'),
                __('app.end_date'),
                __('app.location'),
                // ...,
            ]
        ]);
    }
}
