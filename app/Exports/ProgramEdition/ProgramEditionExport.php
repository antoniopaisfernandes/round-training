<?php

namespace App\Exports\ProgramEdition;

use App\ProgramEdition;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProgramEditionExport implements WithMultipleSheets
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }

    /**
     * @return array
     */
    public function sheets() : array
    {
        return [
            new CoverPageExport($this->programEdition),
            new StudentsExport($this->programEdition),
        ];
    }
}
