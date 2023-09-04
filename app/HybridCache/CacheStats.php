<?php

namespace App\HybridCache;

class CacheStats
{
    public function __construct(
        public int $cacheSize,
        public int $labelCount,
        public int $responseCount,
        public array $labels)
    {
    }
}
