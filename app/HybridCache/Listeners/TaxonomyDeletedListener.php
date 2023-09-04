<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\TaxonomyDeleted;

class TaxonomyDeletedListener
{
    public function handle(TaxonomyDeleted $event)
    {
        HybridCache::invalidateLabelNamespace('taxonomy');
        HybridCache::invalidateLabel('taxonomy', $event->taxonomy->handle());
    }
}
