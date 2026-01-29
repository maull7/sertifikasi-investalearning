<?php

namespace Tests\Feature\Admin;

use App\Models\Certificate;
use App\Models\Exam;
use App\Models\MasterType;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateDownloadPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_certificate_as_pdf(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'status_user' => 'Teraktivasi',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $type = MasterType::query()->create([
            'name_type' => 'WWQ',
        ]);

        $package = Package::query()->create([
            'title' => 'Paket A',
            'id_master_types' => $type->id,
            'status' => 'active',
        ]);

        $exam = Exam::query()->create([
            'package_id' => $package->id,
            'title' => 'Ujian 1',
            'duration' => 60,
            'passing_grade' => 60,
        ]);

        $user = User::factory()->create([
            'role' => 'User',
            'status_user' => 'Teraktivasi',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        TransQuestion::query()->create([
            'id_user' => $user->id,
            'id_package' => $package->id,
            'id_exam' => $exam->id,
            'id_type' => $type->id,
            'questions_answered' => 1,
            'total_questions' => 1,
            'total_score' => 100,
            'status' => 'lulus',
        ]);

        $certificate = Certificate::query()->create([
            'id_user' => $user->id,
            'id_package' => $package->id,
            'id_master_type' => $type->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('certificates.download', $certificate, absolute: false));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}



