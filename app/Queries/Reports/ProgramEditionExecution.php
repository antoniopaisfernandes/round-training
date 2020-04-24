<?php

namespace App\Queries\Reports;

use App\Company;
use App\ProgramEdition;
use Illuminate\Database\Eloquent\Builder;
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
            ->when($this->filters['begin_date'] ?? false, function (Builder $query) {
                $query->where('starts_at', '>=', $this->filters['begin_date']);
            })
            ->when($this->filters['end_date'] ?? false, function (Builder $query) {
                $query->where('starts_at', '<=', $this->filters['end_date']);
            })
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
                '', // interno
                $programEdition->schedules->sum('working_hours'),
                $programEdition->starts_at . ' a ' . $programEdition->ends_at,
                $programEdition->location ?: '',
                ...$purchasingMatrix,
            ];
        });

        return $headers->merge($data);
    }

    private function headers($companyIds)
    {
        return collect([[
            'Formação',
            'Formandos',
            'Interno',
            'Carga horária',
            'Data',
            'Local',
            ...Company::whereIn('id', $companyIds)->get()->pluck('short_name')->flatten()->toArray(),
        ]]);
    }
}
