<?php

namespace App\HybridCache;

use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class StatsProvider
{
    public function getStats(): CacheStats
    {
        $cachePath = storage_path('hybrid-cache');
        $labelsPath = storage_path('hybrid-cache/labels');
        $cacheSize = 0;
        $labelCount = 0;
        $responseCount = 0;
        $labels = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($cachePath)) as $file) {
            if ($file->isFile()) {
                $fileSize = $file->getSize();

                if ($fileSize) {
                    $cacheSize += $fileSize;
                }

                if (Str::startsWith($file->getPath(), $labelsPath)) {
                    $labelCount += 1;

                    $labels[] = $file->getFilename();
                } else {
                    $responseCount += 1;
                }
            }
        }

        // Account for the global invalidation file.
        $responseCount -= 1;

        return new CacheStats($cacheSize, $labelCount, $responseCount, $labels);
    }
}
