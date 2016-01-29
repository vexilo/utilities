<?php
namespace Vexilo\Utilities\Models\Traits;

trait SortableTrait
{
    /**
     * Return an array with the sortable columns
     *
     * @param  array $sort the current sort array
     * @return array
     */
    public static function getSortableColums($sort = null)
    {
        $instance = new static;
        $sortable = $instance->sortable;

        // Sort the array based on the input
        if (is_array($sort)&&count($sort)) {
            foreach ($sort as $item) {
                if (isset($sortable[$item])) {
                    $sortable_aux[$item] = $sortable[$item];
                }
            }
            $sortable = array_merge($sortable_aux, $sortable);
        }

        return $sortable;
    }

    /**
     * Return an array with the searchable columns
     *
     * @return array
     */
    public static function getSearchableColums()
    {
        $instance = new static;
        return $instance->searchable;
    }

    /**
     * Allow to filter results by the search form
     *
     * @param  Illuminate\Database\Query  $query  [description]
     * @param  string $string The search query
     * @return lluminate\Database\Query          [description]
     */
    public function scopeSearchByInput($query, $string = '')
    {
        if ($string) {
            $searchable = self::getSearchableColums();
            $query->where(function ($query) use ($searchable, $string) {
                $string_parts = explode(" ", $string);
                foreach ($string_parts as $part) {
                    $query->where(function ($query) use ($searchable, $part) {
                        foreach ($searchable as $column => $value) {
                            $query->orWhere($column, 'like', '%'.$part.'%');
                        }
                    });
                }
            });
        }

        return $query;
    }

    /**
     * Sort the results by the order form input
     *
     * @param  Illuminate\Database\Query $query
     * @param  array  $sortBy      Order by columns
     * @param  array  $sortByOrder Order by sort colums
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeSortByInput($query, $sortBy = array(), $sortByOrder = array())
    {
        if (is_array($sortBy)&&count($sortBy)) {
            foreach ($sortBy as $column) {
                $query->orderBy($column, ($sortByOrder && in_array($column, $sortByOrder))?'desc':'asc');
            }
        }
        return $query;
    }
}
