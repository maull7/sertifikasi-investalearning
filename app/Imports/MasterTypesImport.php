<?php

namespace App\Imports;

use App\Models\MasterTypes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterTypesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // skip baris kosong
            if (
                empty($row['name_type']) &&
                empty($row['code']) &&
                empty($row['description'])
            ) {
                continue;
            }

            MasterTypes::create([
                'name_type'   => $row['name_type'],
                'code'        => $row['code'] ?? null,
                'description' => $row['description'] ?? null,
            ]);
        }
    }
}
