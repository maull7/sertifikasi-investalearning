<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $menuGroups;

    public function __construct()
    {
        $this->menuGroups = [
            [
                'title' => 'Main Menu',
                'items' => [
                    [
                        'name' => 'Dashboard',
                        'icon' => 'smart-home',
                        'activePattern' => 'dashboard.*',
                        'subItems' => [
                            ['name' => 'Dashboard', 'route' => 'dashboard'],

                        ]
                    ],
                ],
            ],
            [
                'title' => 'Master',
                'items' => [
                    [
                        'name' => 'Master Data',
                        'icon' => 'ti ti-id',
                        'activePattern' => 'master.*',
                        'subItems' => [
                            ['name' => 'Master Jenis', 'route' => 'master-types.index'],
                            ['name' => 'Master Paket', 'route' => 'master-packages.index'],
                            ['name' => 'Master Materi', 'route' => 'master-materials.index'],
                            ['name' => 'Master Mata Pelajaran', 'route' => 'subjects.index'],

                        ]
                    ],
                ],
            ],
            [
                'title' => 'Exams',
                'items' => [
                    [
                        'name' => 'Exams',
                        'icon' => 'ti ti-id',
                        'activePattern' => 'exams.*',
                        'subItems' => [
                            ['name' => 'Exams', 'route' => 'exams.index'],
                            ['name' => 'Bank Questions', 'route' => 'bank-questions.index'],
                            ['name' => 'Mapping Soal', 'route' => 'mapping-questions.index'],
                        ]
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
