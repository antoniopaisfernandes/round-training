<?php

namespace App\Exports\ProgramEdition;

use App\Models\ProgramEdition;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CoverPageExport implements WithEvents, WithTitle
{
    protected $programEdition;

    public function __construct(ProgramEdition $programEdition)
    {
        $this->programEdition = $programEdition;
    }

    public function title(): string
    {
        return 'Program Edition';
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
                $phpspreadsheet->getStyle('B1:B7')->applyFromArray($styleArray);
                $styleArray['fill']['color']['argb'] = '74cbc8';
                $phpspreadsheet->getStyle('A1:A7')->applyFromArray($styleArray);


                $phpspreadsheet->getCell('A1')->setValue('Program Edition');
                $phpspreadsheet->getCell('B1')->setValue($this->programEdition->full_name);
                $phpspreadsheet->getCell('A2')->setValue('Company');
                $phpspreadsheet->getCell('B2')->setValue($this->programEdition->company->name);
                $phpspreadsheet->getCell('A3')->setValue('Supplier');
                $phpspreadsheet->getCell('B3')->setValue($this->programEdition->supplier);
                $phpspreadsheet->getCell('A4')->setValue('Teacher name');
                $phpspreadsheet->getCell('B4')->setValue($this->programEdition->teacher_name);
                $phpspreadsheet->getCell('A5')->setValue('Price');
                $phpspreadsheet->getStyle('B5')->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $phpspreadsheet->getCell('B5')->setValue($this->programEdition->cost);
                $phpspreadsheet->getCell('A6')->setValue('Starts at');
                $phpspreadsheet->getCell('B6')->setValue($this->programEdition->starts_at);
                $phpspreadsheet->getCell('A7')->setValue('Ends at');
                $phpspreadsheet->getCell('B7')->setValue($this->programEdition->ends_at);
            },
        ];
    }
}
