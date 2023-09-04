<?php

namespace App\HybridCache\Commands;

use App\HybridCache\Facades\HybridCache;
use Illuminate\Console\Command;

class InvalidateAll extends Command
{
    protected $signature = 'hybrid-cache:invalidate-all';

    public function __invoke(): void
    {
        $this->info('Invalidating all cache data...');
        HybridCache::invalidateAll();
        $this->info('Invalidating all cache data... done!');
    }
}
