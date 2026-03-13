<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserStatusController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        /** @var array<int>|null $packageIds */
        $packageIds = $request->input('package_ids', []);

        $packages = Package::orderBy('title')->get();

        $query = User::query()
            ->where('role', 'User')
            ->when($search, function ($q, $search): void {
                $q->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->when(! empty($packageIds), function ($q) use ($packageIds): void {
                $q->whereHas('joinedPackages', function ($jq) use ($packageIds): void {
                    $jq->whereIn('id_package', $packageIds);
                });
            })
            ->with(['joinedPackages.package'])
            ->orderBy('name');

        $users = $query->paginate(20)->withQueryString();

        return view('admin.user.status', [
            'users' => $users,
            'packages' => $packages,
            'search' => $search,
            'selectedPackageIds' => array_map('intval', (array) $packageIds),
        ]);
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'action' => ['required', 'in:deactivate,activate'],
        ]);

        $ids = $data['user_ids'];
        $isActive = $data['action'] === 'activate';

        User::whereIn('id', $ids)
            ->where('role', 'User')
            ->update(['is_active' => $isActive]);

        $message = $isActive
            ? 'Akun pengguna yang dipilih berhasil diaktifkan kembali.'
            : 'Akun pengguna yang dipilih berhasil dinonaktifkan.';

        return redirect()
            ->back()
            ->with('success', $message);
    }
}

