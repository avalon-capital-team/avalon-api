<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataPrivacyTypeOption;

class DataPrivacyTypeOptionResource
{
    /**
     * Find by code
     *
     * @param  string $code
     * @return \App\Models\Data\DataPrivacyTypeOption
     */
    public function findByCode(string $code)
    {
        return DataPrivacyTypeOption::where('code', $code)->first();
    }

    /**
     * @return \App\Models\Data\DataPrivacyTypeOption
     */
    public function getAll()
    {
        return DataPrivacyTypeOption::get();
    }

}
