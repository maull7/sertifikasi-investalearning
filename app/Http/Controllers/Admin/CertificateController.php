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
                    'transQuestions as attempt_count' => function ($q) use ($packageId, $typeId) {

                        if ($packageId) {
                            $q->where('id_package', $packageId);
                        }

                        if ($typeId) {
                            $q->where('id_type', $typeId);
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
        /** @var array<int, int> $teacherIds */
        $teacherIds = $request->validated('teacher_ids');

        foreach ($userIds as $userId) {
            $certificate = Certificate::firstOrCreate([
                'id_user' => $userId,
                'id_package' => $packageId,
                'id_master_type' => $typeId,
            ]);

            foreach ($teacherIds as $teacherId) {
                DetailCertificate::firstOrCreate([
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

        $fileName = 'sertifikat-' . Str::slug($certificate->user?->name ?? 'peserta') . '-' . $certificate->id . '.pdf';

        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                // Allow <img src="https://..."> to render in PDF.
                'isRemoteEnabled' => true,
                // More robust HTML parsing for modern markup.
                'isHtml5ParserEnabled' => true,
            ]);

        $httpContext = stream_context_create([
            'http' => [
                'follow_location' => 1,
            ],
            'ssl' => [
                // Prevent HTTPS logo from being blocked due to SSL verification issues (common on local dev).
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

        return $pdf->download($fileName);
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
