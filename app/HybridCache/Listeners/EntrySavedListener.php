<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\EntrySaved;

class EntrySavedListener
{
    public function handle(EntrySaved $event)
    {
        HybridCache::invalidateLabelNamespace('collection');
        HybridCache::invalidateLabel('collection', $event->entry->collection()->handle());
    }
}
