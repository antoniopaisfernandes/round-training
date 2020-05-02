<?php

namespace App\Exports\Reports\Evaluation;

use App\Enrollment;
use App\ProgramEdition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EvaluationExport implements FromCollection, WithEvents, WithTitle, ShouldAutoSize
{
    protected $programEdition;

    public function __construct($options)
    {
        $this->programEdition = ProgramEdition::with(['enrollments.student'])
                                        ->findOrFail($options['program_edition_id'] ?? null);
    }

    public function title(): string
    {
        return 'Avaliação';
    }

    public function collection()
    {
        return $this->programEdition
                    ->enrollments
                    ->map(function (Enrollment $enrollment) {
                        return [
                            'student_name' => $enrollment->student->name,
                            'global_evaluation' => $enrollment->global_evaluation ?: '',
                            'evaluation_comments' => $enrollment->evaluation_comments ?: '',
                            'program_should_be_repeated' => $enrollment->program_should_be_repeated ? 'Sim' : 'Não',
                            'should_be_repeated_in_months' => $enrollment->should_be_repeated_in_months ?: '',
                        ];
                    })
                    ->sortBy('student_name')
                    ->values();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();
                $spreadsheet->insertNewRowBefore(1, 11);

                $this->addLogo($spreadsheet);
                // $this->applyStyle($spreadsheet);
            },
        ];
    }

    private function addLogo($spreadsheet)
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(56);
        $drawing->setWorksheet($spreadsheet);
    }

    private function applyStyle($spreadsheet)
    {
        $spreadsheet->getParent()->getDefaultStyle()->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
        ]);
        $spreadsheet->getStyle('4:7')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '74CBC8',
                ],
            ],
            'font'  => [
                'bold'  =>  true,
            ],
        ]);
    }
}
