<?php

namespace Tests\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class StorageFileProvider extends Base
{
    public function storageFile(string $sourceDirectory, string $storageDirectory, bool $createDirectory = true, ?string $disk = null): string
    {
        $storage = Storage::disk($disk ?? config('filesystems.default'));

        if ($createDirectory && !$storage->exists($storageDirectory)) {
            $storage->makeDirectory($storageDirectory);
        }

        $files = glob($sourceDirectory . '/*');
        $file = $this->randomElement($files);

        $storageFile = $storageDirectory . '/' . basename($file);
        $storage->put($storageFile, file_get_contents($file));
        return $storageFile;
    }
}
