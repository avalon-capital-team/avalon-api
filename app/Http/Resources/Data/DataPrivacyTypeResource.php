<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataPrivacyType;

class DataPrivacyTypeResource
{
    /**
     * Find by code
     *
     * @param  string $code
     * @return \App\Models\Data\DataPrivacyType
     */
    public function findByCode(string $code)
    {
        return DataPrivacyType::where('code', $code)->first();
    }

    /**
     * @return \App\Models\Data\DataPrivacyType
     */
    public function getAll()
    {
        return DataPrivacyType::get();
    }

    /**
     * @return \App\Models\Data\DataPrivacyType
     */
    public function getAllWithOptions()
    {
        $array = [];
        foreach(DataPrivacyType::get() as $type){
            $options = [];
            foreach($type->privacyTypeOptionConnected()->get() as $option){
                $options[] =  $option->privacyTypeOption;
            }
            $array[] = [
                'type' => $type,
                'options' => $options
            ];
        }
        return $array;
    }
}
