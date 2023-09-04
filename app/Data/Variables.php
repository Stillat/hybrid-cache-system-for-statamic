<?php

namespace App\Data;

use App\HybridCache\Facades\HybridCache;
use Statamic\Globals\Variables as StatamicVariables;

class Variables extends StatamicVariables
{
    public function get($key, $fallback = null)
    {
        HybridCache::registerGlobalPath($this->path());

        return parent::get($key, $fallback);
    }
}
