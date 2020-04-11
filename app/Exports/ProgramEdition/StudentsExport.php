<?php

namespace App\Exports\ProgramEdition;

use App\Http\Resources\EnrollmentResource;
use App\ProgramEdition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }

    public function collection()
    {
        return EnrollmentResource::collection($this->programEdition->enrollments()->with(['student', 'company'])->get())
            ->map(function ($enrollment) {
                return [
                    $enrollment->student->name,
                    $enrollment->student->address,
                    $enrollment->student->postal_code,
                    $enrollment->student->city,
                    $enrollment->student->email,
                    $enrollment->company->name,
                    $enrollment->student->current_job_title,
                ];
            });
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
}
