<?php

namespace App\Exports\Student;

use App\Http\Resources\EnrollmentResource;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProgramEditionsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function title(): string
    {
        return 'Program Editions';
    }

    public function headings(): array
    {
        return [
            'Program edition',
            'Supplier',
            'Teacher name',
            'Starts at',
            'Ends at',
            'Hours',
        ];
    }

    public function collection()
    {
        return EnrollmentResource::collection(
            $this->student->enrollments()->with(['programEdition'])->get()
        );
    }

    /**
     * @var Enrollment $enrollment
     */
    public function map($enrollment): array
    {
        return [
            $enrollment->programEdition->full_name,
            $enrollment->programEdition->supplier,
            $enrollment->programEdition->teacher_name,
            $enrollment->programEdition->starts_at,
            $enrollment->programEdition->ends_at,
            $enrollment->programEdition->total_hours,
        ];
    }
}
