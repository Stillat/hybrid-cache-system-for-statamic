<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\EntryCreated;

class EntryCreatedListener
{
    public function handle(EntryCreated $event)
    {
        HybridCache::invalidateLabelNamespace('collection');
        HybridCache::invalidateLabel('collection', $event->entry->collection()->handle());
    }
}
