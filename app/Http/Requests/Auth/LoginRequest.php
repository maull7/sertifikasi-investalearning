<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if ($this->recaptchaRequired()) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        return $rules;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        if ($this->recaptchaRequired()) {
            $this->verifyRecaptcha();
        }

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function recaptchaRequired(): bool
    {
        if (app()->environment('local')) {
            return false;
        }

        return config('services.recaptcha.enabled', true);
    }

    protected function verifyRecaptcha(): void
    {
        $token = $this->string('g-recaptcha-response');
        $secret = config('services.recaptcha.secret_key');

        if (empty($secret)) {
            Log::warning('reCAPTCHA: RECAPTCHA_SECRET_KEY tidak diatur di .env (production)');

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        if ($token->isEmpty()) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token->toString(),
                    'remoteip' => $this->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA request gagal (timeout/koneksi)', [
                'message' => $e->getMessage(),
                'host' => $this->getHost(),
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }

        if (! $response->json('success')) {
            $errorCodes = $response->json('error-codes', []);
            Log::warning('reCAPTCHA verifikasi gagal', [
                'error_codes' => $errorCodes,
                'host' => $this->getHost(),
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
