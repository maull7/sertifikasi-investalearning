@php
    /** @var \App\Models\FaceToFaceSchedule|null $schedule */
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <x-input name="title" label="Judul Jadwal" icon="calendar-event" required
            :value="old('title', $schedule->title ?? '')" placeholder="Contoh: Sesi 5 (19.05 - 20.50) WITA" />
    </div>

    <x-select name="package_id" label="Paket" icon="package" required :placeholder="null">
        <option value="">-- Pilih Paket --</option>
        @foreach ($packages as $package)
            <option value="{{ $package->id }}" @selected((string) old('package_id', $schedule->package_id ?? '') === (string) $package->id)>
                {{ $package->title }}
            </option>
        @endforeach
    </x-select>

    <x-select name="teacher_id" label="Guru" icon="user-star" :placeholder="null">
        <option value="">-- Pilih Guru --</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" @selected((string) old('teacher_id', $schedule->teacher_id ?? '') === (string) $teacher->id)>
                {{ $teacher->name }}
            </option>
        @endforeach
    </x-select>

    <x-select name="subject_id" label="Mata Pelajaran" icon="book" required :placeholder="null">
        <option value="">-- Pilih Mata Pelajaran --</option>
        @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" @selected((string) old('subject_id', $schedule->subject_id ?? '') === (string) $subject->id)>
                {{ $subject->name }}
            </option>
        @endforeach
    </x-select>

    <x-input name="schedule_date" type="date" label="Tanggal" icon="calendar" required
        :value="old('schedule_date', isset($schedule?->schedule_date) ? $schedule->schedule_date->format('Y-m-d') : '')" />

    <x-input name="start_time" type="time" label="Waktu Mulai" icon="clock" required
        :value="old('start_time', $schedule->start_time ?? '')" />

    <x-input name="end_time" type="time" label="Waktu Selesai" icon="clock-hour-4" required
        :value="old('end_time', $schedule->end_time ?? '')" />

    <x-input name="room_name" label="Ruangan" icon="door" required :value="old('room_name', $schedule->room_name ?? '')"
        placeholder="Manual entry oleh admin" />

    <div class="md:col-span-2">
        <x-input name="zoom_join_url" type="url" label="Zoom Join URL (opsional)" icon="link"
            :value="old('zoom_join_url', $schedule->zoom_join_url ?? '')" placeholder="https://zoom.us/j/..." />
    </div>

    <x-input name="zoom_meeting_id" label="Zoom Meeting ID (opsional)" icon="hash"
        :value="old('zoom_meeting_id', $schedule->zoom_meeting_id ?? '')" placeholder="Contoh: 9876543210" />

    <x-input name="zoom_passcode" label="Zoom Passcode (opsional)" icon="key"
        :value="old('zoom_passcode', $schedule->zoom_passcode ?? '')" />

    <div class="md:col-span-2">
        <x-checkbox name="is_active" label="Aktifkan jadwal"
            :checked="(bool) old('is_active', $schedule->is_active ?? true)" value="1" />
    </div>
</div>

