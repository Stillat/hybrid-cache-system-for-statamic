<?php

namespace App\Data;

use App\HybridCache\Facades\HybridCache;
use Statamic\Entries\Entry as StatamicEntry;

class Entry extends StatamicEntry
{
    public function __construct()
    {
        parent::__construct();
    }

    public function id($id = null)
    {
        $entryId = $this->fluentlyGetOrSet('id')->args(func_get_args());

        if ($this->id) {
            HybridCache::registerEntryId($this->id);
        }

        return $entryId;
    }
}
