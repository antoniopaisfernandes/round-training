<?php

namespace App\Exports;

use App\ProgramEdition;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProgramEditionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProgramEdition::all();
    }
}
