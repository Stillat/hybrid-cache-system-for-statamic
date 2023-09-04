<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\TermDeleted;

class TermDeletedListener
{
    public function handle(TermDeleted $event)
    {
        HybridCache::invalidateLabelNamespace('taxonomy');
        HybridCache::invalidateLabel('taxonomy', $event->term->taxonomy()->handle());
    }
}
