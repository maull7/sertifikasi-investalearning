@extends('layouts.app')

@section('title', 'Mapping Mapel - Tambah Baru')

@section('content')
    <div class="space-y-8 pb-20">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Mapping Mapel - Tambah Baru
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Pilih jenis dulu, lalu pilih paket. Setelah itu tentukan mapel apa saja yang masuk ke paket.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="secondary" href="{{ route('mapping-package.index') }}" class="rounded-xl">
                    <i class="ti ti-arrow-left mr-2"></i> Kembali ke Daftar
                </x-button>
            </div>
        </div>

        {{-- Pilih Jenis lalu Paket (Real-time) --}}
        <x-card>
            <form id="packageForm" action="{{ route('mapping-package.create') }}" method="GET" class="space-y-6">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">1. Pilih
                            Jenis</label>
                        <select id="masterTypeSelect" name="master_type_id"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Jenis Dulu --</option>
                            @foreach ($masterTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ (int) ($masterTypeId ?? 0) === $type->id ? 'selected' : '' }}>
                                    {{ $type->name_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">2. Pilih
                            Paket</label>
                        <select id="packageSelect" name="package_id"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                            {{ $masterTypeId ? '' : 'disabled' }}
                            style="{{ $masterTypeId ? '' : 'opacity: 0.6; cursor: not-allowed;' }}">
                            <option value="">-- Pilih Paket --</option>
                            @foreach ($allPackages as $pkg)
                                @if ((int) ($masterTypeId ?? 0) === (int) $pkg['master_type_id'])
                                    <option value="{{ $pkg['id'] }}"
                                        {{ (int) ($packageId ?? 0) === (int) $pkg['id'] ? 'selected' : '' }}>
                                        {{ $pkg['title'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @if ($masterTypeId && !collect($allPackages)->where('master_type_id', $masterTypeId)->count())
                            <p class="mt-1 text-xs text-amber-600 dark:text-amber-400">
                                Tidak ada paket untuk jenis ini.
                            </p>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const masterTypeSelect = document.getElementById('masterTypeSelect');
            const packageSelect = document.getElementById('packageSelect');
            const form = document.getElementById('packageForm');
            const allPackages = @json($allPackages ?? []);

            // Saat jenis berubah, filter paket secara real-time (tanpa submit)
            if (masterTypeSelect && packageSelect) {
                masterTypeSelect.addEventListener('change', function() {
                    const selectedTypeId = this.value;

                    // Reset paket
                    packageSelect.innerHTML = '<option value="">-- Pilih Paket --</option>';
                    packageSelect.disabled = !selectedTypeId;
                    packageSelect.style.opacity = selectedTypeId ? '1' : '0.6';
                    packageSelect.style.cursor = selectedTypeId ? 'default' : 'not-allowed';

                    // Filter paket berdasarkan jenis (real-time tanpa submit)
                    if (selectedTypeId && allPackages && allPackages.length > 0) {
                        const filtered = allPackages.filter(p => parseInt(p.master_type_id) === parseInt(selectedTypeId));
                        filtered.forEach(pkg => {
                            const option = document.createElement('option');
                            option.value = pkg.id;
                            option.textContent = pkg.title;
                            packageSelect.appendChild(option);
                        });
                    }
                });
            }

            // Saat paket dipilih, auto-submit
            if (packageSelect) {
                packageSelect.addEventListener('change', function() {
                    if (this.value && form) {
                        form.submit();
                    }
                });
            }
        });
        </script>
        @endpush

        @if ($selectedPackage)
            <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Paket dipilih: <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $selectedPackage->title }}</span>
                    · Jenis: <span
                        class="text-indigo-600 dark:text-indigo-400">{{ $selectedPackage->masterType->name_type ?? '-' }}</span>
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Deskripsi: {!! \Illuminate\Support\Str::limit($selectedPackage->description, 120) !!}
                </p>
            </div>

            <div class="space-y-8" x-data="{
                    deleteModalOpen: false,
                    deleteUrl: '',
                    subjectName: '',
                    confirmDelete(url, name) {
                        this.deleteUrl = url;
                        this.subjectName = name;
                        this.deleteModalOpen = true;
                    }
                }">

                    {{-- Kiri: Pilih Mapel --}}
                    <div class="space-y-4">
                        <x-card :padding="false" title="Pilih Mapel dari Jenis Paket">
                            <form action="{{ route('mapping-package.store', $selectedPackage) }}" method="POST">
                                @csrf
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr
                                                class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                                <th class="py-3 px-6 w-10"></th>
                                                <th
                                                    class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                    Mapel</th>
                                                <th
                                                    class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                    Kode</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                            @forelse ($availableSubjects as $subject)
                                                <tr
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                                    <td class="py-3 px-6 align-top">
                                                        <input type="checkbox" name="subject_ids[]"
                                                            value="{{ $subject->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    </td>
                                                    <td class="py-3 px-6 align-top">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                            {{ $subject->name }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="py-3 px-6 align-top text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $subject->code ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="py-10">
                                                        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                            Tidak ada mapel tersedia / semua sudah ter-mapping ke paket ini.
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($availableSubjects->isNotEmpty())
                                    <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 flex justify-end">
                                        <x-button type="submit" variant="primary"
                                            class="rounded-xl shadow-lg shadow-indigo-500/20">
                                            Tambah ke Paket
                                        </x-button>
                                    </div>
                                @endif
                            </form>
                        </x-card>
                    </div>

                    {{-- Kanan: Mapel yang sudah di paket --}}
                    <div class="space-y-4">
                        <x-card :padding="false" title="Mapel di Paket Ini">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                                            <th
                                                class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                Mapel</th>
                                            <th
                                                class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                                                Kode</th>
                                            <th
                                                class="py-3 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-right">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                        @forelse ($mappedSubjects as $subject)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors group">
                                                <td class="py-3 px-6 align-top">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300">
                                                        {{ $subject->name }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-6 align-top text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $subject->code ?? '-' }}</td>
                                                <td class="py-3 px-6 align-top">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <x-button variant="danger" size="sm" type="button"
                                                            @click="confirmDelete('{{ route('mapping-package.destroy', [$selectedPackage, $subject]) }}', '{{ $subject->name }}')"
                                                            class="rounded-lg h-9 w-9 p-0 flex items-center justify-center">
                                                            <i class="ti ti-trash text-base"></i>
                                                        </x-button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-10">
                                                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                        Belum ada mapel yang di-mapping ke paket ini.
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </x-card>

                        {{-- Delete Confirmation Modal --}}
                        <div x-show="deleteModalOpen"
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
                            x-cloak x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                            <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800"
                                @click.away="deleteModalOpen = false">
                                <div class="p-8 text-center">
                                    <div
                                        class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                        <i class="ti ti-trash-x text-4xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mapel dari
                                        Paket?
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                        Anda akan menghapus mapel <span class="font-bold text-gray-900 dark:text-white"
                                            x-text="subjectName"></span> dari paket ini. Tindakan ini tidak dapat
                                        dibatalkan.
                                    </p>
                                </div>
                                <div
                                    class="flex gap-3 p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
                                    <x-button variant="secondary" class="flex-1 rounded-xl"
                                        @click="deleteModalOpen = false">
                                        Batal
                                    </x-button>
                                    <form :action="deleteUrl" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" type="submit"
                                            class="w-full rounded-xl shadow-lg shadow-rose-500/20">
                                            Ya, Hapus
                                        </x-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </div>
@endsection
