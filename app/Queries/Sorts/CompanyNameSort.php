<?php

namespace App\Queries\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class CompanyNameSort implements Sort
{
    public function __construct(protected $table, protected $field) {}

    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query->join('companies', "{$this->table}.{$this->field}", '=', 'companies.id')
            ->orderBy('companies.name', $descending ? 'desc' : 'asc');
    }
}
