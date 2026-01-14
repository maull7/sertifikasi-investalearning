<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankQuestionsTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Data contoh untuk template
     */
    public function array(): array
    {
        return [
            [
                'Pilihan Ganda', // tipe_soal
                'Apa ibukota Indonesia?', // soal
                'Jakarta', // opsi_a
                'Bandung', // opsi_b
                'Surabaya', // opsi_c
                'Medan', // opsi_d
                'a', // jawaban
                'Jakarta adalah ibukota negara Indonesia yang terletak di Pulau Jawa.', // pembahasan
                'Ibukota adalah kota yang menjadi pusat pemerintahan suatu negara.', // penjelasan
            ],
            [
                'Pilihan Ganda',
                'Berapa hasil dari 2 + 2?',
                '3',
                '4',
                '5',
                '6',
                'b',
                'Hasil penjumlahan 2 + 2 adalah 4.',
                'Operasi penjumlahan dasar dalam matematika.',
            ],
        ];
    }

    /**
     * Heading untuk Excel
     */
    public function headings(): array
    {
        return [
            'tipe_soal',
            'soal',
            'opsi_a',
            'opsi_b',
            'opsi_c',
            'opsi_d',
            'jawaban',
            'pembahasan',
            'penjelasan',
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20, // tipe_soal
            'B' => 50, // soal
            'C' => 25, // opsi_a
            'D' => 25, // opsi_b
            'E' => 25, // opsi_c
            'F' => 25, // opsi_d
            'G' => 10, // jawaban
            'H' => 50, // pembahasan
            'I' => 50, // penjelasan
        ];
    }
}





