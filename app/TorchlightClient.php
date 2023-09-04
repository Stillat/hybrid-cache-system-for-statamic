<?php

namespace App;

use App\HybridCache\Facades\HybridCache;
use Torchlight\Client;

class TorchlightClient extends Client
{
    protected function throwUnlessProduction($exception)
    {
        HybridCache::abandonCache();

        parent::throwUnlessProduction($exception);
    }
}
