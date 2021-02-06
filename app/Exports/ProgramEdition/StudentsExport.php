<?php

namespace App\Exports\ProgramEdition;

use App\Http\Resources\EnrollmentResource;
use App\Models\ProgramEdition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }

    public function title(): string
    {
        return 'Students';
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Morada',
            'CodPostal',
            'Localidade',
            'Email',
            'Empresa',
            'Função',
        ];
    }

    public function collection()
    {
        return EnrollmentResource::collection(
            $this->programEdition->enrollments()->with(['student', 'company'])->get()
        );
    }

    /**
     * @var Enrollment $enrollment
     */
    public function map($enrollment): array
    {
        return [
            $enrollment->student->name,
            $enrollment->student->address,
            $enrollment->student->postal_code,
            $enrollment->student->city,
            $enrollment->student->email,
            $enrollment->company->name,
            $enrollment->student->current_job_title,
        ];
    }
}
