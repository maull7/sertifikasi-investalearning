<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register-basic');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'profesi' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'institusi' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'jenis_kelamin' => $request->jenis_kelamin,
            'profesi' => $request->profesi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'institusi' => $request->institusi,
            'alamat' => $request->alamat,
            'role' => 'User',
            'avatar' => 'default.jpeg',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        return redirect()->back()->with('success', 'Berhasil melakukan aktivasi, tunggu notifikasi ke email untuk akun dapat di gunakan');
    }
}
