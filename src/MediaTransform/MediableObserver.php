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

            if (isset($attributes[$mediaCollection->name]) && $attributes[$mediaCollection->name]) {
                $storageFile = storage_path('app/' . $attributes[$mediaCollection->name]);

                $model->addMedia($storageFile)->toMediaCollection($mediaCollection->name);
            }
        });
    }
}