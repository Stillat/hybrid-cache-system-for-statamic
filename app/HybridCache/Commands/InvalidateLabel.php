<?php

namespace App\HybridCache\Commands;

use App\HybridCache\Facades\HybridCache;
use Illuminate\Console\Command;

class InvalidateLabel extends Command
{
    protected $signature = 'hybrid-cache:invalidate-label {namespace} {label?}';

    public function __invoke(): void
    {
        $namespace = $this->argument('namespace');
        $label = $this->argument('label');

        $this->info("Invalidating label: {$label}...");
        HybridCache::invalidateCacheLabel($namespace, $label);
        $this->info("Invalidating label: {$label}... done!");
    }
}
