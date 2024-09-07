<?php

namespace App\Queries\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class ProgramNameSort implements Sort
{
    public function __construct(protected $table, protected $field) {}

    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query->join('programs', "{$this->table}.{$this->field}", '=', 'programs.id')
            ->orderBy('programs.name', $descending ? 'desc' : 'asc');
    }
}
