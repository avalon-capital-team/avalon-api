<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataGenre;

class DataGenreResource
{
    /**
     * Find by name
     *
     * @param  string $name
     * @return \App\Models\Data\DataGenre
     */
    public function findByName(string $name)
    {
        return DataGenre::where('name', $name)->first();
    }

    /**
     * @return \App\Models\Data\DataGenre
     */
    public function getAll()
    {
        return DataGenre::get();
    }
}
