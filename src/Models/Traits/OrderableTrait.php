<?php
namespace Vexilo\Utilities\Models\Traits;

trait OrderableTrait
{
    /**
     * Order by the data_order param
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string $order Order direction
     * @return Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeByOrder($query, $order = 'desc')
    {
        $query->orderBy('data_order', $order);
        return $query;
    }
}
