<?php

namespace App\Data;

use App\HybridCache\Facades\HybridCache;
use Statamic\Assets\Asset as StatamicAsset;

class Asset extends StatamicAsset
{
    public function url()
    {
        if ($this->metaExists()) {
            HybridCache::registerAssetPath($this->disk()->path($this->metaPath()));
        } else {
            HybridCache::abandonCache();
        }

        return parent::url();
    }
}
