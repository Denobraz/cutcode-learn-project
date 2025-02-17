<?php

namespace Support\Traits\Models;

use Illuminate\Support\Facades\File;

trait HasThumbnail
{
    abstract public function thumbnailDir(): string;

    public function thumbnailColumn(): string
    {
        return 'thumbnail';
    }
    public function makeThumbnail(string $size, string $method = 'resize'): string
    {
        return route('thumbnail', [
            'size' => $size,
            'method' => $method,
            'file' => File::basename($this->getAttribute($this->thumbnailColumn())),
            'dir' => $this->thumbnailDir(),
        ]);
    }
}
