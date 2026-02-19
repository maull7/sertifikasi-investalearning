<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitorPackageExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    public function __construct(
        public string $packageTitle,
        /** @var array<int, array<int, mixed>> */
        public array $rows
    ) {}

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Rata-rata Tryout'];
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        return $this->rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 35, 'C' => 35, 'D' => 18];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            ],
        ];
    }
}
