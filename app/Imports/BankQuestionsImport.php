<?php

namespace App\Imports;

use App\Models\BankQuestions;
use App\Models\MasterTypes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     * Normalize Excel values before validation (Excel may parse numbers as int/float).
     */
    public function prepareForValidation($data, $index): array
    {
        $castToStringKeys = [
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

        foreach ($castToStringKeys as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null) {
                $data[$key] = (string) $data[$key];
            }
        }

        return $data;
    }

    /**
     * Map row ke model
     */
    public function model(array $row)
    {
        $codeType = trim((string) ($row['kode_jenis'] ?? ''));

        // Find type by name_type (case-insensitive + trimmed)
        $type = MasterTypes::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower($codeType)])
            ->first();

        if (!$type) {
            $this->errors[] = "Tipe soal '{$codeType}' tidak ditemukan pada master_types.";
            return null;
        }

        $questionType = ($row['question_type'] ?? 'Text');
        $questionType = ucfirst(strtolower(trim((string) $questionType)));
        $questionValue = (string) ($row['soal'] ?? '');

        // For Image: allow URL or storage path in "soal"
        if ($questionType === 'Image') {
            $questionValue = $this->resolveImageToStoragePath($questionValue);
            if ($questionValue === null) {
                $this->errors[] = "Gambar soal tidak valid / tidak bisa diunduh pada baris.";
                return null;
            }
        }

        return new BankQuestions([
            'type_id' => $type->id,
            'question_type' => $questionType,
            'question' => $questionValue,
            'option_a' => (string) ($row['opsi_a'] ?? ''),
            'option_b' => (string) ($row['opsi_b'] ?? ''),
            'option_c' => (string) ($row['opsi_c'] ?? ''),
            'option_d' => (string) ($row['opsi_d'] ?? ''),
            'answer' => strtolower($row['jawaban'] ?? ''),
            'solution' => $row['pembahasan'] ?? '',
            'explanation' => $row['penjelasan'] ?? '',
        ]);
    }

    protected function resolveImageToStoragePath(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // Already a storage path (e.g. questions/abc.png)
        if (Str::startsWith($value, 'questions/')) {
            return $value;
        }

        // URL: download into storage/app/public/questions
        if (Str::startsWith($value, ['http://', 'https://'])) {
            try {
                $response = Http::timeout(20)->get($value);
                if (!$response->successful()) {
                    return null;
                }

                $contentType = $response->header('Content-Type', '');
                $extension = match (true) {
                    str_contains($contentType, 'image/jpeg') => 'jpg',
                    str_contains($contentType, 'image/png') => 'png',
                    str_contains($contentType, 'image/webp') => 'webp',
                    default => null,
                };

                if (!$extension) {
                    return null;
                }

                $fileName = now()->timestamp . '_' . Str::random(8) . '.' . $extension;
                $path = 'questions/' . $fileName;
                Storage::disk('public')->put($path, $response->body());

                return $path;
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'kode_jenis' => 'required|string',
            'question_type' => 'required|in:Text,Image,text,image',
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
            'kode_jenis.required' => 'Kode Jenis wajib diisi',
            'question_type.required' => 'Jenis soal wajib diisi',
            'question_type.in' => 'Jenis soal harus Text atau Image',
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
