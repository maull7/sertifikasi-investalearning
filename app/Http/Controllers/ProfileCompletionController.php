<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileCompleteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileCompletionController extends Controller
{
    /**
     * Tampilkan form lengkapi profil (untuk user login Google pertama kali).
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->needsProfileCompletion()) {
            return redirect()->route('user.dashboard');
        }

        return view('profile.complete', [
            'user' => $user,
        ]);
    }

    /**
     * Simpan data profil yang dilengkapi.
     */
    public function update(ProfileCompleteRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $request->user()->save();

        return redirect()->route('user.dashboard')
            ->with('success', 'Profil berhasil dilengkapi. Selamat datang!');
    }
}
