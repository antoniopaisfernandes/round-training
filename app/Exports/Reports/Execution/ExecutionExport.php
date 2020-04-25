<?php

namespace App\Exports\Reports\Execution;

use App\Queries\Reports\ProgramEditionExecution;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExecutionExport implements FromCollection, WithEvents, WithTitle, ShouldAutoSize
{
    protected $query;

    public function __construct($options)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($options, [
            'begin_date' => 'required|date|date_format:Y-m-d',
        ]);

        throw_unless(
            $validator->passes(),
            new ValidationException($validator)
        );

        $this->query = new ProgramEditionExecution([
            'year' => Carbon::create($validator->validated()['begin_date'])->format('Y'),
        ]);
    }

    public function title(): string
    {
        return 'Execução';
    }

    public function collection()
    {
        return $this->query->collection();
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
