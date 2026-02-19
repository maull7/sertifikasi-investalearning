<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitorParticipantExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    /**
     * @param  array<int, array<int, mixed>>  $tryoutRows
     * @param  array<int, array<int, mixed>>  $quizRows
     */
    public function __construct(
        public string $participantName,
        public string $packageTitle,
        public array $tryoutRows,
        public array $quizRows
    ) {}

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        $out = [];
        $out[] = ['Peserta: '.$this->participantName];
        $out[] = ['Paket: '.$this->packageTitle];
        $out[] = [];
        $out[] = ['Riwayat Tryout'];
        $out[] = ['Ujian', 'Nilai', 'Status', 'Waktu'];
        foreach ($this->tryoutRows as $row) {
            $out[] = $row;
        }
        $out[] = [];
        $out[] = ['Riwayat Kuis'];
        $out[] = ['Mapel', 'Kuis', 'Nilai', 'Waktu'];
        foreach ($this->quizRows as $row) {
            $out[] = $row;
        }

        return $out;
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 25, 'C' => 12, 'D' => 18];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            ],
        ];
    }
}
