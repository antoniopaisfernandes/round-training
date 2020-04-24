<?php

namespace App\Queries\Reports;

use App\Company;
use App\ProgramEdition;
use Illuminate\Support\Collection;

class ProgramEditionExecution
{
    /**
     * @return Collection
     */
    public static function collection()
    {
        $programEditions = ProgramEdition::with('enrollments')
            ->get();

        $purchasingMatrix = $programEditions->map
            ->eligible_purchasing_company_ids
            ->flatten()
            ->unique()
            ->values()
            ->flip()
            ->map(fn() => 0)
            ->toArray();

        $headers = self::headers(array_keys($purchasingMatrix));
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

    private static function headers($companyIds)
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
