<?php

namespace App\HybridCache\Commands;

use App\HybridCache\StatsProvider;
use Illuminate\Console\Command;
use Statamic\Support\Str;

class CacheReport extends Command
{
    protected $signature = 'hybrid-cache:report';

    public function __invoke(StatsProvider $cacheStats): void
    {
        $stats = $cacheStats->getStats();

        $this->table([
            'Cache Size',
            'Label Count',
            'Cached Pages',
        ], [
            [
                Str::fileSizeForHumans($stats->cacheSize),
                $stats->labelCount,
                $stats->responseCount,
            ],
        ]);
    }
}
