<?php

namespace App\Tags;

use Statamic\Tags\Taxonomy\Terms as StatamicTerms;

class Terms extends StatamicTerms
{
    public function getTaxonomies()
    {
        return $this->taxonomies->map(function ($taxonomy) {
            return $taxonomy->handle();
        })->all();
    }

    public function getCollections()
    {
        return $this->collections->map(function ($collection) {
            return $collection->handle();
        })->all();
    }
}
