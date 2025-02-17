<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThumbnailController extends Controller
{
    public function __invoke(
        string $dir,
        string $method,
        string $size,
        string $file
    ): BinaryFileResponse
    {
        abort_if(
            !in_array($size, config('thumbnail.allowed_sizes')),
            403,
            'Invalid size'
        );

        $storage = Storage::disk('images');

        $realPath = "$dir/$file";
        $newDirPath = "$dir/$method/$size";
        $newFilePath = "$newDirPath/$file";

        if (!$storage->exists($realPath)) {
            abort(404);
        }

        if (!$storage->exists($newDirPath)) {
            $storage->makeDirectory($newDirPath);
        }

        if (!$storage->exists($newFilePath)) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($storage->path($realPath));
            $image->$method(...explode('x', $size));
            $image->save($storage->path($newFilePath));
        }

        return response()->file($storage->path($newFilePath));
    }
}
