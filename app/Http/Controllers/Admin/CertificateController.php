<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Package;
use App\Models\Teacher;
use Illuminate\View\View;
use App\Models\MasterType;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailCertificate;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\StoreCertificateRequest;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = Certificate::query()
            ->with(['user', 'package', 'type'])
            ->latest()
            ->paginate(10);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create(Request $request): View
    {
        $types = MasterType::query()->orderBy('name_type')->get();

        $typeId = $request->integer('id_master_type') ?: null;
        $packageId = $request->integer('id_package') ?: null;

        $packages = Package::query()
            ->when($typeId, function ($query) use ($typeId) {
                $query->where('id_master_types', $typeId);
            })
            ->orderBy('title')
            ->get();

        $users = collect();
        $teachers = Teacher::query()->orderBy('name')->get();

        if ($typeId || $packageId) {
            $users = User::where('role', 'User')

                ->whereHas('joinedPackages.package', function ($q) use ($packageId, $typeId) {

                    // 🔹 filter paket (kalau dipilih)
                    if ($packageId) {
                        $q->where('packages.id', $packageId);
                    }

                    // 🔹 filter jenis / type dari package (kalau dipilih)
                    if ($typeId) {
                        $q->where('packages.id_master_types', $typeId);
                    }
                })

                ->withCount([
                    'transQuestions as attempt_count' => function ($q) use ($packageId) {

                        if ($packageId) {
                            $q->where('id_package', $packageId);
                        }
                    },

                    'certificates as certificate_count' => function ($q) use ($packageId, $typeId) {

                        if ($packageId) {
                            $q->where('id_package', $packageId);
                        }

                        if ($typeId) {
                            $q->where('id_master_type', $typeId);
                        }
                    },
                ])

                ->orderBy('name')
                ->get();
        }

        return view('admin.certificates.create', compact('types', 'packages', 'typeId', 'packageId', 'users', 'teachers'));
    }

    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        $typeId = (int) $request->validated('id_master_type');
        $packageId = (int) $request->validated('id_package');
        /** @var array<int, int> $userIds */
        $userIds = $request->validated('user_ids');
        /** @var array<string|null> $certificateNumbers */
        $certificateNumbers = $request->validated('certificate_numbers', []);
        /** @var array<string|null> $trainingDateStarts */
        $trainingDateStarts = $request->validated('training_date_starts', []);
        /** @var array<string|null> $trainingDateEnds */
        $trainingDateEnds = $request->validated('training_date_ends', []);
        /** @var array<int, int> $teacherIds */
        $teacherIds = $request->validated('teacher_ids');

        foreach ($userIds as $userId) {
            $certificate = Certificate::create([
                'id_user' => $userId,
                'id_package' => $packageId,
                'id_master_type' => $typeId,
                'certificate_number' => $certificateNumbers[$userId] ?? null,
                'training_date_start' => $trainingDateStarts[$userId] ?? null,
                'training_date_end' => $trainingDateEnds[$userId] ?? null,
            ]);

            foreach ($teacherIds as $teacherId) {
                DetailCertificate::create([
                    'id_certificate' => $certificate->id,
                    'id_teacher' => $teacherId,
                ]);
            }
        }

        return redirect()
            ->route('certificates.index')
            ->with('success', 'Sertifikat berhasil disimpan.');
    }

    public function show(Certificate $certificate): View
    {
        $certificate->load([
            'user',
            'package.materials',
            'type',
            'teachers',
        ]);

        return view('admin.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        $user = auth()->user();
        if ($user && $user->role !== 'Admin' && (int) $certificate->id_user !== (int) $user->id) {
            abort(403);
        }

        $certificate->load([
            'user',
            'package.materials',
            'type',
            'teachers',
        ]);

        $baseName = 'sertifikat-' . Str::slug($certificate->user?->name ?? 'peserta') . '-' . $certificate->id;
        $pngFileName = $baseName . '.png';

        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        $httpContext = stream_context_create([
            'http' => [
                'follow_location' => 1,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        if (method_exists($pdf, 'setHttpContext')) {
            $pdf->setHttpContext($httpContext);
        } elseif (method_exists($pdf, 'getDomPDF') && method_exists($pdf->getDomPDF(), 'setHttpContext')) {
            $pdf->getDomPDF()->setHttpContext($httpContext);
        }

        // Jika ekstensi Imagick tidak tersedia, fallback ke PDF
        if (! class_exists(\Imagick::class)) {
            return $pdf->download($baseName . '.pdf');
        }

        // Konversi PDF menjadi gambar PNG
        $pdfContent = $pdf->output();
        $tmpPath = storage_path('app/certificate-temp-' . $certificate->id . '-' . uniqid('', true) . '.pdf');
        file_put_contents($tmpPath, $pdfContent);

        $imagick = new \Imagick();
        $imagick->setResolution(300, 300);
        
        // Baca semua halaman PDF
        $imagick->readImage($tmpPath);
        
        // Jika ada lebih dari 1 halaman, gabungkan menjadi satu gambar vertikal
        if ($imagick->getNumberImages() > 1) {
            // Set format PNG untuk semua halaman
            $imagick->setImageFormat('png');
            
            // Gabungkan semua halaman menjadi satu gambar vertikal
            $combined = new \Imagick();
            $combined->setResolution(300, 300);
            
            foreach ($imagick as $page) {
                $page->setImageFormat('png');
                $page->setImageCompressionQuality(100);
                $combined->addImage($page);
            }
            
            // Gabungkan semua halaman secara vertikal
            $combined->resetIterator();
            $finalImage = $combined->appendImages(true);
            $finalImage->setImageFormat('png');
            $finalImage->setImageCompressionQuality(100);
            
            $imageData = $finalImage->getImageBlob();
            
            $finalImage->clear();
            $finalImage->destroy();
            $combined->clear();
            $combined->destroy();
        } else {
            // Hanya 1 halaman
            $imagick->setImageFormat('png');
            $imagick->setImageCompressionQuality(100);
            $imageData = $imagick->getImageBlob();
        }

        $imagick->clear();
        $imagick->destroy();
        @unlink($tmpPath);

        return response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $pngFileName . '"',
        ]);
    }

    public function getPackage(MasterType $type)
    {
        return $type->packages()
            ->get();
    }

    public function verify(Certificate $certificate): View
    {
        $certificate->load([
            'user',
            'package',
            'type',
        ]);

        return view('certificates.verify', compact('certificate'));
    }
}
