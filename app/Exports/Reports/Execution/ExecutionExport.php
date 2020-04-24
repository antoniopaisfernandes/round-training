<?php

namespace App\Exports\Reports\Execution;

use App\Queries\Reports\ProgramEditionExecution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExecutionExport implements FromCollection, WithEvents, WithTitle, ShouldAutoSize
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function title(): string
    {
        return 'Execução';
    }

    public function collection()
    {
        return (new ProgramEditionExecution($this->options))->collection();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();
                $spreadsheet->insertNewRowBefore(1, 4);

                $this->addLogo($spreadsheet);
                $this->applyStyle($spreadsheet);
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
        $spreadsheet->getStyle('A:Z')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
        ]);
        $spreadsheet->getStyle('5:5')->applyFromArray([
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
