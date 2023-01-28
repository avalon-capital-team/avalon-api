<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataNotificationType;

class DataNotificationTypeResource
{
    /**
     * Find by code
     *
     * @param  string $code
     * @return \App\Models\Data\DataNotificationType
     */
    public function findByCode(string $code)
    {
        return DataNotificationType::where('code', $code)->first();
    }

    /**
     * @return \App\Models\Data\DataNotificationType
     */
    public function getAll()
    {
        return DataNotificationType::get();
    }
}
