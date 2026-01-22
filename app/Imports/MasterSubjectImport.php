<?php

namespace App\Imports;

use App\Models\MasterType;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterSubjectImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // skip baris kosong
            if (
                empty($row['code_type']) &&
                empty($row['name_subject']) &&
                empty($row['code_subject'])
            ) {
                continue;
            }

            // cari master type berdasarkan code
            $type = MasterType::where('code', $row['code_type'])->first();

            // kalau code_type ga ketemu → skip (atau bisa throw error)
            if (!$type) {
                continue;
            }

            // insert subject
            Subject::create([
                'master_type_id' => $type->id,
                'name'   => $row['name_subject'],
                'code'   => $row['code_subject'] ?? null,
                'description'    => $row['description'] ?? null,
            ]);
        }
    }
}
