<?php

namespace App\Http\Resources\Data;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Data\DataPlan;

class DataPlanResource
{
    /**
     * @return \App\Models\Data\DataPlan
     */
    public function getAll()
    {
        return DataPlan::get();
    }
}
