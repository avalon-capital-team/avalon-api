<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataCountry;

class DataCountryResource
{
    /**
     * Find by name
     *
     * @param  string $name
     * @return \App\Models\Data\DataCountry
     */
    public function findByName(string $name)
    {
        return DataCountry::where('name', $name)->first();
    }

    /**
     * @return \App\Models\Data\DataCountry
     */
    public function getAll()
    {
        return DataCountry::get();
    }
}
