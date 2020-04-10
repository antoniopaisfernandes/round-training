<?php

namespace App\Queries\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class ProgramNameSort implements Sort
{
    protected $table;
    protected $field;

    public function __construct($table, $field)
    {
        $this->table = $table;
        $this->field = $field;
    }

    public function __invoke(Builder $query, bool $descending, string $property)
    {
        return $query->join('programs', "{$this->table}.{$this->field}", '=', 'programs.id')
                    ->orderBy('programs.name', $descending ? 'desc' : 'asc');
    }
}
