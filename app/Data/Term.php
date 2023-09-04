<?php

namespace App\Data;

use App\HybridCache\Facades\HybridCache;
use Statamic\Taxonomies\Term as StatamicTerm;

class Term extends StatamicTerm
{
    public function id()
    {
        $termId = parent::id();

        if ($termId) {
            HybridCache::registerTermId($termId);
        }

        return $termId;
    }
}
