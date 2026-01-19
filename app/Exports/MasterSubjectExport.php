<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class MasterSubjectExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths
{
    public function headings(): array
    {
        return [
            'code_type',
            'name_subject',
            'code_subject',
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
            'A' => 20, // name_type
            'B' => 20, // code
            'C' => 20, // description
            'D' => 50, // description
        ];
    }
}
