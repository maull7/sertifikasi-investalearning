@php
    /** @var \App\Models\FaceToFaceSchedule|null $schedule */
    $existingSessions = [];
    if (isset($schedule) && $schedule->sessions->isNotEmpty()) {
        foreach ($schedule->sessions as $s) {
            $existingSessions[] = [
                'name'         => $s->name,
                'session_date' => $s->session_date->format('Y-m-d'),
                'start_time'   => substr((string) $s->start_time, 0, 5),
                'end_time'     => substr((string) $s->end_time, 0, 5),
                'teacher_id'   => (string) ($s->teacher_id ?? ''),
            ];
        }
    }
    $sessionsJson = json_encode(
        old('sessions', $existingSessions ?: [['name'=>'','session_date'=>'','start_time'=>'','end_time'=>'','teacher_id'=>'']]),
        JSON_UNESCAPED_UNICODE
    );
@endphp

{{-- Header fields --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <x-input name="title" label="Judul Jadwal" icon="calendar-event" required
            :value="old('title', $schedule->title ?? '')" placeholder="Contoh: Kelas Pasar Modal Batch 5" />
    </div>

    <x-select name="package_id" label="Paket" icon="package" required :placeholder="null">
        <option value="">-- Pilih Paket --</option>
        @foreach ($packages as $package)
            <option value="{{ $package->id }}" @selected((string) old('package_id', $schedule->package_id ?? '') === (string) $package->id)>
                {{ $package->title }}
            </option>
        @endforeach
    </x-select>

    <x-input name="room_name" label="Ruangan" icon="door" required
        :value="old('room_name', $schedule->room_name ?? '')" placeholder="Contoh: Zoom / Ruang A" />

    <div class="md:col-span-2">
        <x-input name="zoom_join_url" type="url" label="Zoom Join URL (opsional)" icon="link"
            :value="old('zoom_join_url', $schedule->zoom_join_url ?? '')" placeholder="https://zoom.us/j/..." />
    </div>

    <x-input name="zoom_meeting_id" label="Zoom Meeting ID (opsional)" icon="hash"
        :value="old('zoom_meeting_id', $schedule->zoom_meeting_id ?? '')" />

    <x-input name="zoom_passcode" label="Zoom Passcode (opsional)" icon="key"
        :value="old('zoom_passcode', $schedule->zoom_passcode ?? '')" />

    <div class="md:col-span-2">
        <x-checkbox name="is_active" label="Aktifkan jadwal"
            :checked="(bool) old('is_active', $schedule->is_active ?? true)" value="1" />
    </div>
</div>

{{-- Sessions --}}
<div class="mt-8"
    x-data="{
        sessions: {{ $sessionsJson }},
        addSession() {
            this.sessions.push({ session_date: '', start_time: '', end_time: '', teacher_id: '' });
        },
        removeSession(i) {
            if (this.sessions.length > 1) this.sessions.splice(i, 1);
        }
    }">

    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Sesi Jadwal</h3>
        <button type="button" @click="addSession()"
            class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 text-xs font-semibold text-white transition-colors">
            <i class="ti ti-plus"></i> Tambah Sesi
        </button>
    </div>

    @error('sessions')
        <p class="mb-2 text-xs text-rose-500">{{ $message }}</p>
    @enderror

    <div class="space-y-3">
        <template x-for="(session, i) in sessions" :key="i">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/40 p-4">

                {{-- Nama Sesi --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Sesi <span class="text-rose-500">*</span></label>
                    <input type="text" :name="`sessions[${i}][name]`" x-model="session.name"
                        placeholder="Contoh: Sesi 1 - Pengenalan"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" :name="`sessions[${i}][session_date]`" x-model="session.session_date"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                </div>

                {{-- Waktu Mulai --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                    <input type="time" :name="`sessions[${i}][start_time]`" x-model="session.start_time"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                </div>

                {{-- Waktu Selesai --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                    <input type="time" :name="`sessions[${i}][end_time]`" x-model="session.end_time"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required />
                </div>

                {{-- Guru --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Guru</label>
                    <select :name="`sessions[${i}][teacher_id]`" x-model="session.teacher_id"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Hapus --}}
                <div class="flex items-end">
                    <button type="button" @click="removeSession(i)"
                        x-show="sessions.length > 1"
                        class="w-full rounded-xl border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 px-3 py-2 text-xs font-semibold transition-colors">
                        <i class="ti ti-trash mr-1"></i> Hapus
                    </button>
                </div>

            </div>
        </template>
    </div>
</div>
