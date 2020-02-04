<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

/**
 * Class CategoriesImport
 *
 * @package App\Imports
 */
class CategoriesImport implements ToCollection, ToArray
{

    /**
     * @param Collection $collection
     *
     * @return Collection
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function array(array $array)
    {
        return $array;
    }
}
