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

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_certificate_for_user(): void
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

        $teacher = \App\Models\Teacher::query()->create([
            'name' => 'Guru 1',
            'nip' => '12345',
            'email' => 'guru@example.com',
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

        $response = $this
            ->actingAs($admin)
            ->post(route('certificates.store', absolute: false), [
                'id_master_type' => $type->id,
                'id_package' => $package->id,
                'user_ids' => [$user->id],
                'teacher_ids' => [$teacher->id],
            ]);

        $response->assertRedirect(route('certificates.index', absolute: false));

        $this->assertDatabaseHas('certificates', [
            'id_user' => $user->id,
            'id_package' => $package->id,
            'id_master_type' => $type->id,
        ]);

        $certificate = Certificate::query()->firstOrFail();

        $this->assertSame($user->id, $certificate->id_user);

        $this->assertDatabaseHas('detail_certificates', [
            'id_certificate' => $certificate->id,
            'id_teacher' => $teacher->id,
        ]);
    }
}
