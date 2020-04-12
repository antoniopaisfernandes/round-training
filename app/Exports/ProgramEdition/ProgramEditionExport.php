<?php

namespace App\Exports\ProgramEdition;

use App\ProgramEdition;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProgramEditionExport implements WithMultipleSheets
{
    use ConditionallyLoadsAttributes;

    protected $programEdition;
    protected $options;

    public function __construct(ProgramEdition $programEdition, array $options = [])
    {
        $this->programEdition = $programEdition;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function sheets() : array
    {
        return $this->filter([
            $this->when($this->options['cover'] ?? true, new CoverPageExport($this->programEdition)),
            $this->when($this->options['students'] ?? true, new StudentsExport($this->programEdition)),
        ]);
    }
}
