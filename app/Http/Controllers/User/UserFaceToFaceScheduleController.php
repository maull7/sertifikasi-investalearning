<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\FaceToFaceScheduleInvitationMail;
use App\Models\FaceToFaceSchedule;
use App\Models\FaceToFaceScheduleRegistration;
use App\Models\UserJoin;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserFaceToFaceScheduleController extends Controller
{
    public function index(): View
    {
        return $this->renderSchedulePage('all');
    }

    public function registered(): View
    {
        return $this->renderSchedulePage('registered');
    }

    public function register(FaceToFaceSchedule $faceToFaceSchedule): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->userCanAccessSchedule((int) $user->id, $faceToFaceSchedule->id)) {
            abort(403, 'Anda belum terdaftar pada paket jadwal ini.');
        }

        $registration = FaceToFaceScheduleRegistration::query()->firstOrCreate(
            [
                'schedule_id' => $faceToFaceSchedule->id,
                'user_id' => $user->id,
            ],
            [
                'participant_email' => (string) $user->email,
            ]
        );

        $joinUrl = $registration->zoom_join_url ?: $faceToFaceSchedule->zoom_join_url;

        if (! $registration->wasRecentlyCreated && $joinUrl) {
            return redirect()
                ->route('user.face-to-face-schedules.index')
                ->with('success', 'Anda sudah terdaftar di jadwal ini.');
        }

        $zoomRegistration = $this->registerZoomParticipant($faceToFaceSchedule, (string) $user->name, (string) $user->email);
        if (! empty($zoomRegistration['join_url'])) {
            $joinUrl = (string) $zoomRegistration['join_url'];
            $registration->zoom_join_url = $joinUrl;
        }
        if (! empty($zoomRegistration['registrant_id'])) {
            $registration->zoom_registrant_id = (string) $zoomRegistration['registrant_id'];
        }

        if (! $joinUrl) {
            $joinUrl = '#';
        }

        Mail::to($user->email)->send(new FaceToFaceScheduleInvitationMail($faceToFaceSchedule->loadMissing(['package', 'teacher', 'subject']), $joinUrl));

        $registration->participant_email = (string) $user->email;
        $registration->invitation_sent_at = now();
        $registration->save();

        return redirect()
            ->route('user.face-to-face-schedules.index')
            ->with('success', 'Pendaftaran berhasil. Undangan Zoom sudah dikirim ke email Anda.');
    }

    private function joinedPackageIds(int $userId): array
    {
        return UserJoin::query()
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->pluck('id_package')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    private function userCanAccessSchedule(int $userId, int $scheduleId): bool
    {
        $schedule = FaceToFaceSchedule::query()->find($scheduleId);
        if (! $schedule) {
            return false;
        }

        return UserJoin::query()
            ->where('user_id', $userId)
            ->where('id_package', $schedule->package_id)
            ->where('status', 'approved')
            ->exists();
    }

    private function registerZoomParticipant(FaceToFaceSchedule $schedule, string $name, string $email): array
    {
        $meetingId = trim((string) $schedule->zoom_meeting_id);
        $token = trim((string) config('services.zoom.server_to_server_token', ''));

        if ($meetingId === '' || $token === '') {
            return [];
        }

        $endpoint = rtrim((string) config('services.zoom.base_url', 'https://api.zoom.us/v2'), '/');

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->post($endpoint . '/meetings/' . $meetingId . '/registrants', [
                    'email' => $email,
                    'first_name' => Str::before($name, ' ') ?: $name,
                    'last_name' => Str::after($name, ' ') ?: '.',
                ]);

            $response->throw();
        } catch (RequestException $exception) {
            Log::warning('Gagal registrasi peserta ke Zoom.', [
                'schedule_id' => $schedule->id,
                'meeting_id' => $meetingId,
                'email' => $email,
                'error' => $exception->getMessage(),
            ]);

            return [];
        }

        $payload = $response->json();

        return [
            'join_url' => $payload['join_url'] ?? null,
            'registrant_id' => $payload['registrant_id'] ?? null,
        ];
    }

    private function renderSchedulePage(string $activeTab): View
    {
        $user = Auth::user();
        $joinedPackageIds = $this->joinedPackageIds((int) $user->id);

        $allSchedules = FaceToFaceSchedule::query()
            ->with(['package:id,title', 'sessions.teacher:id,name'])
            ->where('is_active', true)
            ->whereIn('package_id', $joinedPackageIds)
            ->orderByDesc('id')
            ->get();

        $registeredScheduleIds = FaceToFaceScheduleRegistration::query()
            ->where('user_id', $user->id)
            ->pluck('schedule_id')
            ->all();

        $registeredSchedules = FaceToFaceSchedule::query()
            ->with(['package:id,title', 'sessions.teacher:id,name'])
            ->whereIn('id', $registeredScheduleIds)
            ->orderByDesc('id')
            ->get();

        return view('user.face-to-face-schedules.index', compact(
            'allSchedules',
            'registeredSchedules',
            'registeredScheduleIds',
            'activeTab',
        ));
    }
}
