<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestMasterUser;
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
            ->when($search, fn($q, $v) => $q->where('name', 'like', '%' . $v . '%')
                ->orWhere('email', 'like', '%' . $v . '%'))
            ->when($roleFilter !== null && $roleFilter !== '', fn($q) => $q->where('role', $roleFilter))
            ->orderBy('role')
            ->orderBy('name');

        $users = $query->paginate(15)->withQueryString();

        return view('admin.master-user.index', compact('users', 'search', 'roleFilter'));
    }

    public function create(): View
    {
        return view('admin.master-user.create');
    }

    public function store(RequestMasterUser $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['status_user'] = 'Teraktivasi';
        unset($validated['password_confirmation']);

        User::query()->create($validated);

        return redirect()
            ->route('master-user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        if (! in_array($user->role, ['Admin', 'Petugas'], true)) {
            abort(404);
        }

        return view('admin.master-user.edit', compact('user'));
    }

    public function update(RequestMasterUser $request, User $user): RedirectResponse
    {
        if (! in_array($user->role, ['Admin', 'Petugas'], true)) {
            abort(404);
        }

        $validated = $request->validated();
        if (! empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $user->update($validated);

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
