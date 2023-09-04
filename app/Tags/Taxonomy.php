<?php

namespace App\Tags;

use App\HybridCache\Facades\HybridCache;
use Statamic\Tags\Taxonomy\Taxonomy as StatamicTaxonomy;

class Taxonomy extends StatamicTaxonomy
{
    public function index()
    {
        $terms = new Terms($this->params);

        foreach ($terms->getTaxonomies() as $term) {
            HybridCache::label('taxonomy', $term);
        }

        foreach ($terms->getCollections() as $collection) {
            HybridCache::label('collection', $collection);
        }

        return parent::index();
    }
}
