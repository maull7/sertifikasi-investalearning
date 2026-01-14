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
                'Matematika Dasar', // tipe_soal (isi harus sama persis dengan master_types.name_type)
                'Text', // question_type (Text / Image)
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
                'Matematika Dasar',
                'Text',
                'Berapa hasil dari 2 + 2?',
                '3',
                '4',
                '5',
                '6',
                'b',
                'Hasil penjumlahan 2 + 2 adalah 4.',
                'Operasi penjumlahan dasar dalam matematika.',
            ],
            [
                'Matematika Dasar',
                'Image',
                'https://example.com/soal/soal-1.png',
                'A',
                'B',
                'C',
                'D',
                'a',
                'Jawaban yang benar adalah A.',
                'Contoh soal dengan gambar. Kolom "soal" diisi URL gambar atau path storage (contoh: questions/soal-1.png).',
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
            'question_type',
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
            'B' => 15, // question_type
            'C' => 50, // soal
            'D' => 25, // opsi_a
            'E' => 25, // opsi_b
            'F' => 25, // opsi_c
            'G' => 25, // opsi_d
            'H' => 10, // jawaban
            'I' => 50, // pembahasan
            'J' => 50, // penjelasan
        ];
    }
}





