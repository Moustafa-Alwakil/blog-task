<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    public static function bootSluggable(): void
    {
        static::saved(function (self $model) {
            $model->{$model->slugColumn ?? 'slug'} = Str::slug($model->{$model->sluggableColumn}) . '-' .$model->id;
            $model->saveQuietly();
        });
    }
}
