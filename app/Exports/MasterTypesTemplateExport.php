<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MasterTypesTemplateExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    public function headings(): array
    {
        return [
            'name_type',
            'code',
            'description',
        ];
    }

    public function array(): array
    {
        return []; // template kosong
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // indigo
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // name_type
            'B' => 20, // code
            'C' => 40, // description
        ];
    }
}
