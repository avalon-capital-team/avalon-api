<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class UuidHelper
{
    /**
     * @return string
     */
    public static function generate($model)
    {
        do {
            $uuid = (string) UuidHelper::generateNewUuid();
        } while ($model->where('uuid', $uuid)->exists());

        return $uuid;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public static function generateNewUuid()
    {
        return Uuid::uuid4();
    }
}
