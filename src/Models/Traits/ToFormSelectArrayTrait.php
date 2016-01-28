<?php
namespace Models\Traits;

use \Extensions\VexiloCollection;

trait ToFormSelectArrayTrait
{
    /**
     * Override the new Collection method with a custmo collection
     *
     * @param  array  $models
     * @return \Extensions\VexiloCollection
     */
    public function newCollection(array $models = [])
    {
        return new VexiloCollection($models);
    }
}
