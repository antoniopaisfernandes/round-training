<?php

namespace App\Exports\ProgramEdition;

use App\ProgramEdition;

class CoverPageExport
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }
}
