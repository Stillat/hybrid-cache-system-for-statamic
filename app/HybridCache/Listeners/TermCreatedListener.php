<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\TermCreated;

class TermCreatedListener
{
    public function handle(TermCreated $event)
    {
        HybridCache::invalidateLabelNamespace('taxonomy');
        HybridCache::invalidateLabel('taxonomy', $event->term->taxonomy()->handle());
    }
}
