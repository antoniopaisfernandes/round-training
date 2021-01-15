<?php

namespace App\Exports\Student;

use App\Exports\Student\ProgramEditionsExport;
use App\Models\Student;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExport implements WithMultipleSheets
{
    use ConditionallyLoadsAttributes;

    protected $student;
    protected $options;

    public function __construct(Student $student, array $options = [])
    {
        $this->student = $student;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function sheets() : array
    {
        return $this->filter([
            $this->when($this->options['cover'] ?? true, new CoverPageExport($this->student)),
            $this->when($this->options['program_editions'] ?? true, new ProgramEditionsExport($this->student)),
        ]);
    }
}
