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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
                $this->addHeader($spreadsheet);
                // $this->applyStyle($spreadsheet);
            },
        ];
    }

    private function addLogo(Worksheet $spreadsheet)
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(56);
        $drawing->setWorksheet($spreadsheet);
    }

    private function addHeader(Worksheet $spreadsheet)
    {
        $spreadsheet->setCellValue('B2', 'Avaliação da Eficácia da Formação');
        $spreadsheet->setCellValue('A5', "Ação de Formação: {$this->programEdition->full_name}");
        $spreadsheet->setCellValue('A6', "Objectivos da ação de Formação: {$this->programEdition->goals}");
        $spreadsheet->setCellValue('D5', "Duração: {$this->programEdition->schedules->sum('working_hours')} horas");
        $spreadsheet->setCellValue('E5', "Data: {$this->programEdition->starts_at}");

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
