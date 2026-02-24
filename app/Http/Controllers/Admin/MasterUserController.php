<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestMasterUser;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role');

        $query = User::query()
            ->whereIn('role', ['Admin', 'Petugas'])
            ->when($search, fn ($q, $v) => $q->where('name', 'like', '%'.$v.'%')
                ->orWhere('email', 'like', '%'.$v.'%'))
            ->when($roleFilter !== null && $roleFilter !== '', fn ($q) => $q->where('role', $roleFilter))
            ->with('managedPackages:id,title')
            ->orderBy('role')
            ->orderBy('name');

        $users = $query->paginate(15)->withQueryString();

        return view('admin.master-user.index', compact('users', 'search', 'roleFilter'));
    }

    public function create(): View
    {
        $packages = Package::where('status', 'active')->orderBy('title')->get();

        return view('admin.master-user.create', compact('packages'));
    }

    public function store(RequestMasterUser $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['status_user'] = 'Teraktivasi';
        unset($validated['password_confirmation']);

        $managedPackageIds = $validated['managed_package_ids'] ?? [];
        unset($validated['managed_package_ids']);

        /** @var \App\Models\User $user */
        $user = User::query()->create($validated);

        if ($user->role === 'Petugas') {
            $user->managedPackages()->sync($managedPackageIds);
        }

        return redirect()
            ->route('master-user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        if (! in_array($user->role, ['Admin', 'Petugas'], true)) {
            abort(404);
        }

        $user->load('managedPackages');
        $packages = Package::where('status', 'active')->orderBy('title')->get();

        return view('admin.master-user.edit', compact('user', 'packages'));
    }

    public function show(User $user): View
    {
        if (! in_array($user->role, ['Admin', 'Petugas'], true)) {
            abort(404);
        }

        $user->load('managedPackages');

        return view('admin.master-user.show', compact('user'));
    }

    public function update(RequestMasterUser $request, User $user): RedirectResponse
    {
        if (! in_array($user->role, ['Admin', 'Petugas'], true)) {
            abort(404);
        }

        $validated = $request->validated();
        $managedPackageIds = $validated['managed_package_ids'] ?? [];
        unset($validated['managed_package_ids']);
        if (! empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $user->update($validated);

        if ($user->role === 'Petugas') {
            $user->managedPackages()->sync($managedPackageIds);
        } else {
            $user->managedPackages()->detach();
        }

        return redirect()
            ->route('master-user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data user');
    }
}
