<?php

namespace App\HybridCache\Listeners;

use App\HybridCache\Facades\HybridCache;
use Statamic\Events\EntryDeleted;

class EntryDeletedListener
{
    public function handle(EntryDeleted $event)
    {
        HybridCache::invalidateLabelNamespace('collection');
        HybridCache::invalidateLabel('collection', $event->entry->collection()->handle());
    }
}
