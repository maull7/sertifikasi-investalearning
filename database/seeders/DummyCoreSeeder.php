<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\MasterType;
use App\Models\Package;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyCoreSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function (): void {
            // Users
            $admin = User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Administrator',
                    'password' => bcrypt('12345678'),
                    'role' => 'Admin',
                    'status_user' => 'Teraktivasi',
                    'jenis_kelamin' => 'Laki-laki',
                ]
            );

            $staffUsers = User::factory()->count(3)->create([
                'role' => 'Petugas',
            ]);

            $participantUsers = User::factory()->count(10)->create([
                'role' => 'User',
            ]);

            // Master Types
            $types = collect([
                ['name_type' => 'Try Out SKD', 'code' => 'SKD', 'description' => 'Paket latihan soal SKD.'],
                ['name_type' => 'Try Out SKB', 'code' => 'SKB', 'description' => 'Paket latihan soal SKB.'],
                ['name_type' => 'Try Out TPA', 'code' => 'TPA', 'description' => 'Paket latihan soal TPA umum.'],
            ])->map(function (array $data): MasterType {
                return MasterType::firstOrCreate(
                    ['code' => $data['code']],
                    [
                        'name_type' => $data['name_type'],
                        'description' => $data['description'],
                    ]
                );
            });

            // Packages
            $packages = collect();

            foreach ($types as $type) {
                $packages = $packages->merge(
                    Package::factory()
                        ->count(2)
                        ->active()
                        ->create([
                            'id_master_types' => $type->id,
                        ])
                );
            }

            // Subjects
            $subjects = collect();

            foreach ($types as $type) {
                $subjects = $subjects->merge(
                    Subject::factory()
                        ->count(4)
                        ->create([
                            'master_type_id' => $type->id,
                        ])
                );
            }

            // Map subjects ke packages (package_subject)
            foreach ($packages as $package) {
                $subjectIds = $subjects
                    ->where('master_type_id', $package->id_master_types)
                    ->pluck('id')
                    ->shuffle()
                    ->take(3)
                    ->all();

                $package->mappedSubjects()->sync($subjectIds);
            }

            // Map staff ke packages (package_staff)
            foreach ($packages as $package) {
                $staffIds = $staffUsers->random(min(2, $staffUsers->count()))->pluck('id')->all();
                $package->staff()->sync($staffIds);
            }

            // Exams per package
            foreach ($packages as $package) {
                Exam::factory()
                    ->count(2)
                    ->create([
                        'package_id' => $package->id,
                    ]);
            }
        });
    }
}

