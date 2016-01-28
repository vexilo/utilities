<?php

namespace Vexilo\Utilities\Extensions;

use Illuminate\Database\Eloquent\Collection;

class VexiloCollection extends Collection
{
    /**
     * Allow to convert some object to a compatible array to populate
     * a form
     *
     * @param  string $key   The name of the parameter that will be used as key
     * @param  string $label The name of the parameter that will be used as label
     * @param  bool $appendEmpty If you want to add some default empty option at first
     * @param  bool $appendEmptyLabel The label
     * @return array
     */
    public function toFormSelectArray(
        $key = 'id',
        $label = 'label',
        $appendEmpty = false,
        $appendEmptyLabel = ''
    ) {
        $res = $this->reduce(function ($result, $item) use ($key, $label) {
            $result[$item->{$key}] = $item->{$label};
            return $result;
        });

        if ($appendEmpty) {
            $res = array("" => $appendEmptyLabel) + $res;
        }

        return $res;
    }
}
