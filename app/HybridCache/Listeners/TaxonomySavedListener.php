<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\TaxonomySaved;

class TaxonomySavedListener
{
    public function handle(TaxonomySaved $event)
    {
        HybridCache::invalidateLabelNamespace('taxonomy');
        HybridCache::invalidateLabel('taxonomy', $event->taxonomy->handle());
    }
}
