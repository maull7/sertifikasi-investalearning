<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterType;
use App\Models\Package;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MappingPackageController extends Controller
{
    /**
     * Daftar paket (dengan opsi mapping mapel). Mirip daftar ujian di mapping soal.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $packages = Package::with(['masterType', 'mappedSubjects'])
            ->when($search, fn($q, $v) => $q->where('title', 'like', '%' . $v . '%')
                ->orWhereHas('masterType', fn($q2) => $q2->where('name_type', 'like', '%' . $v . '%')))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mapping-package.index', [
            'packages' => $packages,
            'search' => $search,
        ]);
    }

    /**
     * Tambah mapping mapel: pilih jenis dulu, lalu paket (real-time filtering).
     */
    public function create(Request $request): View
    {
        $masterTypeId = $request->query('master_type_id');
        $packageId = $request->query('package_id');

        $masterTypes = MasterType::orderBy('name_type')->get();

        // Kirim semua paket untuk filtering real-time di JavaScript
        $allPackages = Package::with('masterType')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'title' => $pkg->title,
                'master_type_id' => $pkg->id_master_types,
                'master_type_name' => $pkg->masterType->name_type ?? '-',
            ])
            ->values()
            ->all();

        $selectedPackage = null;
        $availableSubjects = collect();
        $mappedSubjects = collect();

        if ($packageId) {
            $selectedPackage = Package::with('masterType')->find($packageId);
            if ($selectedPackage) {
                // Pastikan master_type_id ter-set jika belum ada di query
                if (! $masterTypeId) {
                    $masterTypeId = $selectedPackage->id_master_types;
                }
                $mappedSubjectIds = $selectedPackage->mappedSubjects()->pluck('subjects.id');
                $availableSubjects = Subject::whereNotIn('id', $mappedSubjectIds)
                    ->orderBy('name')
                    ->get();
                $mappedSubjects = $selectedPackage->mappedSubjects()->orderBy('name')->get();
            }
        }

        return view('admin.mapping-package.create', [
            'masterTypes' => $masterTypes,
            'masterTypeId' => $masterTypeId,
            'allPackages' => $allPackages,
            'selectedPackage' => $selectedPackage,
            'availableSubjects' => $availableSubjects,
            'mappedSubjects' => $mappedSubjects,
            'packageId' => $packageId,
        ]);
    }

    /**
     * Halaman mapping mapel untuk satu paket: pilih mapel dari jenis paket, daftar mapel yang sudah di-paket.
     */
    public function manage(Package $package): View
    {
        $package->load('masterType');

        $mappedSubjectIds = $package->mappedSubjects()->pluck('subjects.id');
        $availableSubjects = Subject::whereNotIn('id', $mappedSubjectIds)
            ->orderBy('name')
            ->get();

        $mappedSubjects = $package->mappedSubjects()->orderBy('name')->get();

        return view('admin.mapping-package.manage', [
            'package' => $package,
            'availableSubjects' => $availableSubjects,
            'mappedSubjects' => $mappedSubjects,
        ]);
    }

    /**
     * Tambah mapel ke paket.
     */
    public function store(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'subject_ids' => ['required', 'array'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
        ]);

        $subjectIds = $validated['subject_ids'];
        $package->mappedSubjects()->syncWithoutDetaching($subjectIds);

        return redirect()
            ->route('mapping-package.manage', $package)
            ->with('success', 'Mapel berhasil ditambahkan ke paket.');
    }

    /**
     * Hapus mapel dari paket.
     */
    public function destroy(Package $package, Subject $subject): RedirectResponse
    {
        $package->mappedSubjects()->detach($subject->id);

        return redirect()
            ->route('mapping-package.manage', $package)
            ->with('success', 'Mapel berhasil dihapus dari paket.');
    }
}
