<?php

namespace App\Http\Resources\Data;

use App\Models\Data\DataNotificationChannel;

class DataNotificationChannelResource
{
    /**
     * Find by code
     *
     * @param  string $code
     * @return \App\Models\Data\DataNotificationChannel
     */
    public function findByCode(string $code)
    {
        return DataNotificationChannel::where('code', $code)->first();
    }
}
