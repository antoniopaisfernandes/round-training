<?php

namespace App\Exports\Reports\Evaluation;

use App\Models\Enrollment;
use App\Models\ProgramEdition;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class EvaluationExport implements FromView, WithEvents
{
    protected $programEdition;

    public function __construct($options)
    {
        $this->programEdition = ProgramEdition::with(['enrollments.student'])
                                        ->findOrFail($options['program_edition_id'] ?? null);
        $this->rows = $this->programEdition
                        ->enrollments
                        ->map(function (Enrollment $enrollment) {
                            return [
                                'student_name' => $enrollment->student->name,
                                'global_evaluation' => $enrollment->global_evaluation ?: '',
                                'evaluation_comments' => $enrollment->evaluation_comments ?: '',
                                'program_should_be_repeated' => $enrollment->program_should_be_repeated,
                                'program_should_not_be_repeated' => $enrollment->program_should_be_repeated === 0,
                                'should_be_repeated_in_months' => $enrollment->should_be_repeated_in_months ?: '',
                            ];
                        })
                        ->sortBy('student_name')
                        ->values();
    }

    public function view(): View
    {
        return view('Exports::Evaluation.main', [
            'programEdition' => $this->programEdition,
            'logo' => public_path('images/logo.png'),
            'rows' => $this->rows,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();

                $spreadsheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $spreadsheet->getPageSetup()->setFitToWidth(true)->setHorizontalCentered(true);

                $spreadsheet->getStyle('A2:F5')->applyFromArray([
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
                $spreadsheet->getStyle('A8:F9')->applyFromArray([
                    'font'  => [
                        'bold'  =>  true,
                    ],
                ]);
                $spreadsheet->getStyle('A5:F5')->getAlignment()->setWrapText(true);
                $spreadsheet->getStyle('A10:F' . ($this->rows->count() + 9))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);
            },
        ];
    }
}
