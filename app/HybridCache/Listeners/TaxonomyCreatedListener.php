<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\TaxonomyCreated;

class TaxonomyCreatedListener
{
    public function handle(TaxonomyCreated $event)
    {
        HybridCache::invalidateLabelNamespace('taxonomy');
        HybridCache::invalidateLabel('taxonomy', $event->taxonomy->handle());
    }
}
