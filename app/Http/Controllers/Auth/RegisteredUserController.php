<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'profesi' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'institusi' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if (! app()->environment('local') && config('services.recaptcha.enabled', true)) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        $request->validate($rules);

        if (! app()->environment('local') && config('services.recaptcha.enabled', true)) {
            $this->verifyRecaptcha($request);
        }

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
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        return redirect()->route('login')->with('success', 'Berhasil melakukan aktivasi, tunggu notifikasi ke email untuk akun dapat di gunakan');
    }

    protected function verifyRecaptcha(Request $request): void
    {
        $token = (string) $request->input('g-recaptcha-response', '');
        $secret = config('services.recaptcha.secret_key');

        if ($secret === '') {
            Log::warning('reCAPTCHA: RECAPTCHA_SECRET_KEY tidak diatur di .env (production)');

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        if ($token === '') {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA request gagal (timeout/koneksi)', [
                'message' => $e->getMessage(),
                'host' => $request->getHost(),
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        if (! $response->json('success')) {
            $errorCodes = $response->json('error-codes', []);
            Log::warning('reCAPTCHA verifikasi gagal', [
                'error_codes' => $errorCodes,
                'host' => $request->getHost(),
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }
    }
}
