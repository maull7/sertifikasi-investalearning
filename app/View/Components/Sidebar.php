<?php

namespace App\View\Components;

use App\Models\User;
use App\Models\UserJoin;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $menuGroups;

    public int $unverifiedCount;

    public int $pendingPackageCount = 0;

    public bool $isAdmin;

    public bool $isStaff;

    public function __construct()
    {
        $user = Auth::user();
        $this->isAdmin = $user && $user->role === 'Admin';
        $this->isStaff = $user && in_array($user->role, ['Admin', 'Petugas'], true);

        if ($this->isStaff) {
            $this->menuGroups = $this->getStaffMenu($this->isAdmin);
            $this->unverifiedCount = User::query()
                ->where('role', 'User')
                ->where('status_user', 'Belum Teraktivasi')
                ->count();
            $this->pendingPackageCount = UserJoin::where('status', 'pending')->count();
        } else {
            $this->menuGroups = $this->getUserMenu();
            $this->unverifiedCount = 0;
            $this->pendingPackageCount = 0;
        }
    }

    /**
     * Menu untuk Admin dan Petugas. Petugas tanpa Master & Pelatihan; Master User hanya Admin.
     */
    private function getStaffMenu(bool $isAdminOnly): array
    {
        $groups = [
            [
                'title' => 'Main Menu',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'smart-home',
                        'activePattern' => 'dashboard || user.not.active || approve-packages.*',
                        'subItems' => [
                            ['name' => 'Dashboard', 'route' => 'dashboard'],
                            ['name' => 'Aktivasi akun', 'route' => 'user.not.active'],
                            ['name' => 'Persetujuan Pendaftaran Paket', 'route' => 'approve-packages.index'],
                        ],
                    ],
                    [
                        'name' => 'Profile',
                        'icon' => 'user',
                        'route' => 'profile.edit',
                    ],
                ],
            ],
        ];

        if ($isAdminOnly) {
            $groups[] = [
                'title' => 'Master',
                'items' => [
                    [
                        'name' => 'Master Data',
                        'icon' => 'ti ti-id',
                        'activePattern' => 'master.*|teacher.*|books.*|subjects.*',
                        'subItems' => [
                            ['name' => 'Master Jenis', 'route' => 'master-types.index'],
                            ['name' => 'Master Mata Pelajaran', 'route' => 'subjects.index'],
                            ['name' => 'Master Paket', 'route' => 'master-packages.index'],
                            ['name' => 'Master Materi', 'route' => 'master-materials.index'],
                            ['name' => 'Data Pengajar', 'route' => 'teacher.index'],
                            ['name' => 'Data Buku', 'route' => 'books.index'],
                            ['name' => 'Master User', 'route' => 'master-user.index'],
                        ],
                    ],
                ],
            ];
            $groups[] = [
                'title' => 'Pelatihan',
                'items' => [
                    [
                        'name' => 'Pelatihan',
                        'icon' => 'ti ti-adjustments-search',
                        'activePattern' => 'exams.*|bank-questions.*|mapping-questions.*|mapping-package.*|quizzes.*',
                        'subItems' => [
                            ['name' => 'Mapping Mapel', 'route' => 'mapping-package.index'],
                            ['name' => 'Try Out', 'route' => 'exams.index'],
                            ['name' => 'Kuis / Latihan', 'route' => 'quizzes.index'],
                            ['name' => 'Bank Soal', 'route' => 'bank-questions.index'],
                            ['name' => 'Mapping Soal', 'route' => 'mapping-questions.index'],

                        ],
                    ],
                ],
            ];
        }

        $groups[] = [
            'title' => 'Monitor',
            'items' => [
                [
                    'name' => 'Monitor Peserta',
                    'icon' => 'ti ti-chart-line',
                    'activePattern' => 'monitor-participants.*',
                    'subItems' => [
                        ['name' => 'Monitor Peserta', 'route' => 'monitor-participants.index'],
                    ],
                ],
            ],
        ];
        $groups[] = [
            'title' => 'Hasil',
            'items' => [
                [
                    'name' => 'Hasil Nilai',
                    'icon' => 'ti ti-report-analytics',
                    'activePattern' => 'show-grades.*',
                    'subItems' => [
                        ['name' => 'Nilai', 'route' => 'show-grades.index'],
                    ],
                ],
            ],
        ];
        $groups[] = [
            'title' => 'Sertifikat',
            'items' => [
                [
                    'name' => 'Data Sertifikat',
                    'icon' => 'ti ti-certificate',
                    'activePattern' => 'certificates.*',
                    'subItems' => [
                        ['name' => 'Tambah Sertifikat', 'route' => 'certificates.create'],
                        ['name' => 'Data Sertifikat', 'route' => 'certificates.index'],
                    ],
                ],
            ],
        ];

        return $groups;
    }

    private function getUserMenu(): array
    {
        return [
            [
                'title' => 'Main Menu',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'smart-home',
                        'activePattern' => 'user.dashboard',
                        'subItems' => [
                            ['name' => 'Dashboard', 'route' => 'user.dashboard'],
                        ],
                    ],
                    [
                        'name' => 'Profile',
                        'icon' => 'user',
                        'route' => 'profile.edit',
                    ],
                ],
            ],
            [
                'title' => 'Kursus',
                'items' => [
                    [
                        'name' => 'Package / Kursus',
                        'icon' => 'book',
                        'activePattern' => 'user.packages.*',
                        'subItems' => [
                            ['name' => 'Semua Package', 'route' => 'user.packages.index'],
                        ],
                    ],
                    [
                        'name' => 'Package Saya',
                        'icon' => 'bookmark',
                        'activePattern' => 'user.my-packages.*',
                        'subItems' => [
                            ['name' => 'Package Saya', 'route' => 'user.my-packages.index'],
                        ],
                    ],
                    // [
                    //     'name' => 'Riwayat Ujian',
                    //     'icon' => 'list-check',
                    //     'activePattern' => 'user.history-exams.*',
                    //     'subItems' => [
                    //         ['name' => 'Riwayat Ujian', 'route' => 'user.history-exams.index'],
                    //     ],
                    // ],

                ],
            ],
            [
                'title' => 'Sertifkat',
                'items' => [
                    [
                        'name' => 'Sertifikat',
                        'icon' => 'ti ti-certificate',
                        'activePattern' => 'user.certificate.*',
                        'subItems' => [
                            ['name' => 'Sertifikat Saya', 'route' => 'user.certificate.index'],
                        ],
                    ],

                ],
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('layouts.sidebar');
    }
}
