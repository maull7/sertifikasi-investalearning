<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('patch')

    {{-- Row 1 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-input
            label="Nama Lengkap"
            name="name"
            placeholder="Masukkan nama lengkap"
            :value="old('name', $user->name)"
            required
        />

        <x-input
            label="Email"
            name="email"
            type="email"
            placeholder="Masukkan email"
            :value="old('email', $user->email)"
            required
        />
    </div>

    {{-- Row 2 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-input
            label="Nomor Telepon"
            name="phone"
            placeholder="Masukkan nomor telepon"
            :value="old('phone', $user->phone)"
            required
        />

        <x-select
            label="Jenis Kelamin"
            name="jenis_kelamin"
        >
            <option value="">Pilih jenis kelamin</option>
            <option value="Laki-laki" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Laki-laki')>
                Laki-laki
            </option>
            <option value="Perempuan" @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'Perempuan')>
                Perempuan
            </option>
        </x-select>
    </div>

    {{-- Row 3 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-input
            label="Profesi"
            name="profesi"
            placeholder="Masukkan profesi"
            :value="old('profesi', $user->profesi)"
        />

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Lahir
            </label>
               <input 
                type="date" 
                 name="tanggal_lahir" 
                                value="{{ old('tanggal_lahir',$user->tanggal_lahir) }}"
                                class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-4 py-3"
                            />
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror  

        </div>
    </div>

    {{-- Row 4 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-input
            label="Institusi"
            name="institusi"
            placeholder="Masukkan institusi"
            :value="old('institusi', $user->institusi)"
        />

        <x-textarea
            label="Alamat"
            name="alamat"
            rows="3"
            placeholder="Masukkan alamat lengkap"
        >
            {{ old('alamat', $user->alamat) }}
        </x-textarea>
    </div>

    {{-- Action --}}
    <div class="flex items-center gap-4">
        <x-button type="submit" variant="primary">
            Save
        </x-button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400"
            >
                Saved.
            </p>
        @endif
    </div>
</form>

</section>
