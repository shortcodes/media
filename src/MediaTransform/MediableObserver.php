<?php

namespace Shortcodes\Media\Observers;


class MediableObserver
{
    public function saved($model)
    {

        if (!$model->mediaCollections) {
            $model->registerMediaCollections();
        }

        collect($model->mediaCollections)->each(function ($mediaCollection) use ($model) {

            $attributes = request()->all();

            if (isset($attributes[$mediaCollection->name]) && $attributes[$mediaCollection->name] && strpos($attributes[$mediaCollection->name], '/tmp/') !== false) {

                $filePath = ltrim(strstr($attributes[$mediaCollection->name], '/tmp/'),'/');

                $storageFile = storage_path('app/' . $filePath);

                $model->addMedia($storageFile)->toMediaCollection($mediaCollection->name);
            }
        });
    }
}