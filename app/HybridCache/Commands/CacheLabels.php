<?php

namespace App\HybridCache\Commands;

use App\HybridCache\StatsProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CacheLabels extends Command
{
    protected $signature = 'hybrid-cache:labels';

    public function __invoke(StatsProvider $cacheStats): void
    {
        $stats = $cacheStats->getStats();

        $this->table([
            'Label',
            'Clear Command',
        ],
            collect($stats->labels)->map(function ($label) {
                $labelDisplay = 'php artisan hybrid-cache:invalidate-label ';

                if (Str::contains($label, '__')) {
                    $namespace = Str::before($label, '__');
                    $labelName = Str::after($label, '__');
                    $labelDisplay .= $namespace.' '.$labelName;
                } else {
                    $labelDisplay .= $label;
                }

                return [$label, $labelDisplay];
            })
        );
    }
}
