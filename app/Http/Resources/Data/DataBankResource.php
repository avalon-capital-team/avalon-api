<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Data\DataBank;

class DataBankResource
{
    /**
     * @return \App\Models\Data\DataPlan
     */
    public function getAll()
    {
        return DataBank::get();
    }
}
