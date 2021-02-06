<?php

namespace App\Exports\Student;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CoverPageExport implements WithEvents, WithTitle
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function title(): string
    {
        return 'Student';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $phpspreadsheet = $event->sheet->getDelegate();
                $phpspreadsheet->getColumnDimension('A')->setAutoSize(true);
                $phpspreadsheet->getColumnDimension('B')->setAutoSize(true);

                $styleArray = [
                    'font'  => [
                        'size'  => 20,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ];
                $phpspreadsheet->getStyle('B1:B6')->applyFromArray($styleArray);
                $styleArray['fill']['color']['argb'] = '74cbc8';
                $phpspreadsheet->getStyle('A1:A6')->applyFromArray($styleArray);

                $phpspreadsheet->getCell('A1')->setValue('Name');
                $phpspreadsheet->getCell('B1')->setValue($this->student->name);
                $phpspreadsheet->getCell('A2')->setValue('Address');
                $phpspreadsheet->getCell('B2')->setValue($this->student->address);
                $phpspreadsheet->getCell('A3')->setValue('Postal code');
                $phpspreadsheet->getCell('B3')->setValue($this->student->postal_code);
                $phpspreadsheet->getCell('A4')->setValue('City');
                $phpspreadsheet->getCell('B4')->setValue($this->student->city);
                $phpspreadsheet->getCell('A5')->setValue('Company');
                $phpspreadsheet->getCell('B5')->setValue($this->student->company->name);
                $phpspreadsheet->getCell('A6')->setValue('Job title');
                $phpspreadsheet->getCell('B6')->setValue($this->student->current_job_title);
            },
        ];
    }
}
