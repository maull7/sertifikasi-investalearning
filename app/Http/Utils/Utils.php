<?php

namespace App\Http\Utils;

use Illuminate\Support\Carbon;

class Utils
{
    public function periodToDate(?string $period): ?Carbon
    {
        if ($period === 'all' || ! $period) {
            return null;
        }
        $now = Carbon::now();

        return match ($period) {
            '7d' => $now->copy()->subDays(7),
            '30d' => $now->copy()->subDays(30),
            '3m' => $now->copy()->subMonths(3),
            default => null,
        };
    }
}
