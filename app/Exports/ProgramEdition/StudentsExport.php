<?php

namespace App\Exports\ProgramEdition;

use App\ProgramEdition;

class StudentsExport
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }
}
