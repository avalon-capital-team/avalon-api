<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Store file
     *
     * @param  $file
     * @param  string $folder
     * @return void
     */
    public function storeFile($file, string $folder)
    {
        $storage = Storage::disk('digitalocean')->putFile('uploads/' . $folder, $file, 'public');
        $fileUpload_url = config('filesystems.disks.digitalocean.endpoint') . '/' . config('filesystems.disks.digitalocean.bucket') . '/' . $storage;
        return $fileUpload_url;
    }
}
