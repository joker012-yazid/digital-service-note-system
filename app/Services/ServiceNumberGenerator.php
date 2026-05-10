<?php

namespace App\Services;

use App\Models\ServiceNote;

class ServiceNumberGenerator
{
    public function generate(?int $year = null): string
    {
        $year ??= (int) now()->format('Y');
        $prefix = "SN-{$year}-";

        $latestServiceNo = ServiceNote::withTrashed()
            ->where('service_no', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('service_no')
            ->value('service_no');

        $nextNumber = $latestServiceNo
            ? ((int) substr($latestServiceNo, -4)) + 1
            : 1;

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
