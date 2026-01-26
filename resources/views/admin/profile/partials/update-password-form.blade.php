<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <x-input
            label="Password Lama"
            type="password"
            name="current_password"
            placeholder="Masukan password lama"
            :value="old('current_password')"
            required
        />
        <x-input
            label="Password Baru"
             type="password"
            name="password"
            placeholder="Masukan password baru"
            :value="old('password')"
            required
        />
        <x-input
            label="Konfirmasi Password Baru"
             type="password"
            name="password_confirmation"
            placeholder="Konfirmasi password baru"
            :value="old('password_confirmation')"
            required
        />
        <div class="flex items-center gap-4">
            <x-button type="submit" variant="primary">
                Save
            </x-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
