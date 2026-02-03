@extends('layouts.app')

@section('title', 'Buat Sertifikat')

@section('content')
    <div class="space-y-6 pb-20" x-data="{ teacherModalOpen: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Buat Sertifikat</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Pilih jenis & paket, lalu pilih peserta yang akan dibuatkan sertifikat
                </p>
            </div>

            <x-button variant="secondary" href="{{ route('certificates.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        <x-card title="Filter">
            <form method="GET" action="{{ route('certificates.create') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Jenis</label>
                    <select name="id_master_type"
                        id="type"
                        class="mt-2 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih Jenis</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected((int) $typeId === $type->id)>{{ $type->name_type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Paket</label>
                    <select name="id_package"
                        id="package"
                        class="mt-2 w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Pilih Paket</option>
                      
                    </select>
                </div>

                <div class="flex items-end">
                    <x-button type="submit" variant="primary" class="rounded-xl w-full">
                        Tampilkan Peserta
                    </x-button>
                </div>
            </form>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </x-card>

        @if($typeId && $packageId)
            <x-card :padding="false" title="Pilih Peserta">
                <form method="POST" action="{{ route('certificates.store') }}">
                    @csrf
                    <input type="hidden" name="id_master_type" value="{{ $typeId }}">
                    <input type="hidden" name="id_package" value="{{ $packageId }}">

                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Pilih</th>
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Peserta</th>
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Status Ujian</th>
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center whitespace-nowrap">Sertifikat</th>
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Nomor Sertifikat</th>
                                    <th class="py-3 px-4 sm:px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider whitespace-nowrap">Tanggal Pelatihan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                @forelse($users as $index => $user)
                                    @php
                                        $attemptCount = (int) ($user->attempt_count ?? 0);
                                        $certificateCount = (int) ($user->certificate_count ?? 0);
                                        $hasAttempt = $attemptCount > 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors" x-data="{ selected: false }">
                                        <td class="py-3 px-4 sm:px-6">
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                @disabled(!$hasAttempt || $certificateCount > 0)
                                                @change="selected = $event.target.checked"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 disabled:opacity-40 user-checkbox">
                                        </td>
                                        <td class="py-3 px-4 sm:px-6">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-sm text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $user->email }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 sm:px-6 text-center">
                                            @if($hasAttempt)
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                                    Sudah mengerjakan ({{ $attemptCount }})
                                                </span>
                                            @else
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                                                    Belum mengerjakan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 sm:px-6 text-center">
                                            @if($certificateCount > 0)
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                                    Sudah dibuat
                                                </span>
                                            @else
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                                    Belum dibuat
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 sm:px-6">
                                            <div x-show="selected" x-cloak>
                                                <input type="text" 
                                                    name="certificate_numbers[{{ $user->id }}]" 
                                                    placeholder="Nomor Sertifikat"
                                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                                                    required>
                                            </div>
                                            <span x-show="!selected" class="text-xs text-gray-400">-</span>
                                        </td>
                                        <td class="py-3 px-4 sm:px-6">
                                            <div x-show="selected" x-cloak class="flex flex-col gap-2">
                                                <div>
                                                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tanggal Mulai</label>
                                                    <input type="date" 
                                                        name="training_date_starts[{{ $user->id }}]" 
                                                        class="w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                                                        required>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Tanggal Selesai</label>
                                                    <input type="date" 
                                                        name="training_date_ends[{{ $user->id }}]" 
                                                        class="w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm dark:text-white outline-none focus:ring-2 focus:ring-indigo-500"
                                                        required>
                                                </div>
                                            </div>
                                            <span x-show="!selected" class="text-xs text-gray-400">-</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-24">
                                            <div class="flex flex-col items-center justify-center text-center max-w-[320px] mx-auto">
                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                    <i class="ti ti-users-off text-2xl text-gray-400"></i>
                                                </div>
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                                    Tidak ada peserta
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Tidak ada peserta yang ditemukan.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 sm:px-6 lg:px-8 py-6 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                        <x-button type="button" variant="primary" class="rounded-xl"
                            @click="
                                const checked = Array.from($el.closest('form').querySelectorAll('.user-checkbox:checked'));
                                if (checked.length === 0) {
                                    alert('Pilih minimal satu peserta terlebih dahulu.');
                                    return;
                                }
                                
                                // Validasi nomor sertifikat dan tanggal pelatihan untuk setiap peserta yang dipilih
                                let isValid = true;
                                let missingFields = [];
                                
                                checked.forEach(checkbox => {
                                    const userId = checkbox.value;
                                    const certNumber = $el.closest('form').querySelector(`input[name='certificate_numbers[${userId}]']`);
                                    const trainingDateStart = $el.closest('form').querySelector(`input[name='training_date_starts[${userId}]']`);
                                    const trainingDateEnd = $el.closest('form').querySelector(`input[name='training_date_ends[${userId}]']`);
                                    
                                    if (!certNumber || !certNumber.value.trim()) {
                                        isValid = false;
                                        missingFields.push('Nomor Sertifikat');
                                    }
                                    if (!trainingDateStart || !trainingDateStart.value.trim()) {
                                        isValid = false;
                                        missingFields.push('Tanggal Mulai Pelatihan');
                                    }
                                    if (!trainingDateEnd || !trainingDateEnd.value.trim()) {
                                        isValid = false;
                                        missingFields.push('Tanggal Selesai Pelatihan');
                                    }
                                    
                                    // Validasi tanggal selesai harus setelah atau sama dengan tanggal mulai
                                    if (trainingDateStart && trainingDateEnd && trainingDateStart.value && trainingDateEnd.value) {
                                        if (new Date(trainingDateEnd.value) < new Date(trainingDateStart.value)) {
                                            isValid = false;
                                            alert(`Tanggal selesai harus setelah atau sama dengan tanggal mulai untuk peserta ${userId}.`);
                                            return;
                                        }
                                    }
                                });
                                
                                if (!isValid) {
                                    alert('Mohon lengkapi Nomor Sertifikat, Tanggal Mulai, dan Tanggal Selesai Pelatihan untuk semua peserta yang dipilih.');
                                    return;
                                }
                                
                                teacherModalOpen = true;
                            ">
                            Simpan Sertifikat
                        </x-button>
                    </div>

                    {{-- Modal Pilih Pengajar --}}
                    <div x-show="teacherModalOpen"
                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                        x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95">
                        <div
                            class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                            @click.away="teacherModalOpen = false">
                            <div class="px-6 pt-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Pilih Pengajar</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Pilih pengajar yang akan ditampilkan di sertifikat.
                                    </p>
                                </div>
                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="teacherModalOpen = false">
                                    <i class="ti ti-x text-lg"></i>
                                </button>
                            </div>

                            <div class="max-h-80 overflow-y-auto px-6 py-4 space-y-2">
                                @forelse($teachers as $teacher)
                                    <label class="flex items-center justify-between gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/60 cursor-pointer">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $teacher->name }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $teacher->email ?? '-' }} @if($teacher->nip) • NIP: {{ $teacher->nip }} @endif
                                            </span>
                                        </div>
                                        <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data pengajar.
                                    </p>
                                @endforelse
                            </div>

                            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                                <x-button type="button" variant="secondary" class="rounded-xl" @click="teacherModalOpen = false">
                                    Batal
                                </x-button>
                                <x-button type="submit" variant="primary" class="rounded-xl">
                                    Simpan Sertifikat
                                </x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>
        @else
            <x-card>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Pilih <span class="font-semibold">Jenis</span> dan <span class="font-semibold">Paket</span>, lalu klik
                    <span class="font-semibold">Tampilkan Peserta</span>.
                </div>
            </x-card>
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('type').addEventListener('change', function () {
    let typeId = this.value;
    let packageSelect = document.getElementById('package');

    packageSelect.innerHTML = '<option value="">Loading...</option>';

    if (!typeId) {
        packageSelect.innerHTML = '<option value="">Pilih Paket</option>';
        return;
    }

    fetch(`/get-package/${typeId}`)
        .then(res => res.json())
        .then(data => {
            packageSelect.innerHTML = '<option value="">Pilih Paket</option>';

            data.forEach(pkg => {
                packageSelect.innerHTML += `
                    <option value="${pkg.id}">${pkg.title}</option>
                `;
            });
        });
});
</script>

@endpush

