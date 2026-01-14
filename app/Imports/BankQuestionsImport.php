<?php

namespace App\Imports;

use App\Models\BankQuestions;
use App\Models\MasterTypes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class BankQuestionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $errors = [];

    /**
     * Map row ke model
     */
    public function model(array $row)
    {
        // Find type by name
        $type = MasterTypes::where('name_type', $row['tipe_soal'])->first();
        
        if (!$type) {
            $this->errors[] = "Tipe soal '{$row['tipe_soal']}' tidak ditemukan pada baris.";
            return null;
        }

        return new BankQuestions([
            'type_id' => $type->id,
            'question' => $row['soal'] ?? '',
            'option_a' => $row['opsi_a'] ?? '',
            'option_b' => $row['opsi_b'] ?? '',
            'option_c' => $row['opsi_c'] ?? '',
            'option_d' => $row['opsi_d'] ?? '',
            'answer' => strtolower($row['jawaban'] ?? ''),
            'solution' => $row['pembahasan'] ?? '',
            'explanation' => $row['penjelasan'] ?? '',
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'tipe_soal' => 'required|string',
            'soal' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'jawaban' => 'required|in:a,A,b,B,c,C,d,D',
            'pembahasan' => 'required|string',
            'penjelasan' => 'required|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'tipe_soal.required' => 'Tipe soal wajib diisi',
            'soal.required' => 'Soal wajib diisi',
            'opsi_a.required' => 'Opsi A wajib diisi',
            'opsi_b.required' => 'Opsi B wajib diisi',
            'opsi_c.required' => 'Opsi C wajib diisi',
            'opsi_d.required' => 'Opsi D wajib diisi',
            'jawaban.required' => 'Jawaban wajib diisi',
            'jawaban.in' => 'Jawaban harus A, B, C, atau D',
            'pembahasan.required' => 'Pembahasan wajib diisi',
            'penjelasan.required' => 'Penjelasan wajib diisi',
        ];
    }

    /**
     * Get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}




