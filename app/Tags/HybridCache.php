<?php

namespace App\Tags;

use App\HybridCache\Facades\HybridCache as Cache;
use Carbon\Carbon;
use Statamic\Tags\Tags;

class HybridCache extends Tags
{
    protected static $handle = 'hybrid_cache';

    public function bypassed()
    {
        return Cache::isCacheBypassed();
    }

    public function ignore()
    {
        Cache::abandonCache();
    }

    public function expire()
    {
        $ttl = $this->params->get(['ttl', 'in'], null);

        if ($ttl != null) {
            Cache::setExpiration(Carbon::now()->addSeconds($ttl)->timestamp);

            return;
        }

        $on = $this->params->get(['on', 'at'], null);

        if ($on != null) {
            Cache::setExpiration(Carbon::parse($on)->timestamp);
        }
    }

    public function label()
    {
        foreach ($this->params as $paramName => $value) {
            if (! is_string($value)) {
                continue;
            }

            Cache::label($paramName, $value);
        }
    }
}
