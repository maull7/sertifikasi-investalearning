<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class Header extends Component
{
    public object $user;


    public function __construct()
    {
        // Simulasi Data User
        $this->user = (object) [
            'name' => 'Rafael Nuansa',
            'email' => 'rafaelnuansa@dev.test',
            'avatar' => null,
            'initials' => 'RN',
            'role' => 'Super Admin',
        ];

        $this->unreadCount = 3;
    }

    public function render(): View|Closure|string
    {
        return view('layouts.header');
    }
}
