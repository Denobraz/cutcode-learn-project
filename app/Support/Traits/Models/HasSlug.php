<?php

namespace App\Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            if ($model->slug === null) {
                $slug = $originSlug = str($model->{self::slugFrom()})->slug();

                $version = 1;
                while (static::query()->where('slug', $slug)->exists()) {
                    $slug = str($originSlug)->append('-' . $version)->slug();
                    $version++;
                }

                $model->slug = $slug;
            }
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
