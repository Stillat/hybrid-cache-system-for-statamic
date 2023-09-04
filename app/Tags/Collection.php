<?php

namespace App\Tags;

use App\HybridCache\Facades\HybridCache;
use Illuminate\Pagination\LengthAwarePaginator;
use Statamic\Tags\Collection\Collection as StatamicCollection;

class Collection extends StatamicCollection
{
    protected function output($items)
    {
        $outputItems = $items;

        if ($outputItems instanceof LengthAwarePaginator) {
            $outputItems = $outputItems->items();
        }

        collect($outputItems)->map(function ($item) {
            return $item->collection()->handle();
        })->unique()->each(function ($collection) {
            HybridCache::label('collection', $collection);
        });

        return parent::output($items);
    }
}
