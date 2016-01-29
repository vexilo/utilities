<?php
namespace Vexilo\Utilities\Models\Traits;

trait OrderableTrait
{
    public function scopeByOrder($query)
    {
        $query->orderBy('data_order', 'desc');
        return $query;
    }
}
