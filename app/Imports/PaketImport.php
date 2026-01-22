<?php

namespace App\Imports;

use App\Models\MasterType;
use App\Models\Package;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaketImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // skip baris kosong
            if (
                empty($row['code_type']) &&
                empty($row['name_package'])
            ) {
                continue;
            }

            // cari master type berdasarkan code
            $type = MasterType::where('code', $row['code_type'])->first();

            // kalau code_type ga ketemu → skip (atau bisa throw error)
            if (!$type) {
                continue;
            }

            // insert paket
            Package::create([
                'id_master_types' => $type->id,
                'title'   => $row['name_package'],
                'description'    => $row['description'],
                'status' => 'active',
            ]);
        }
    }
}
