<?php

namespace Shortcodes\Media\Observers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Shortcodes\Media\Models\MediaLibrary;
use Spatie\MediaLibrary\Models\Media;

class MediableObserver
{
    public function saving(Model $model)
    {
        $this->getMediablesFromAttributes($model);
    }

    public function saved(Model $model)
    {
        $this->handleMediables($model);
    }

    private function getMediablesFromAttributes(Model $model)
    {
        $model->mediables = array_intersect_key($model->getAttributes(), $this->getMediableLikeProperties($model));
        $model->setRawAttributes(array_diff_key($model->getAttributes(), $model->mediables));
    }

    private function getMediableLikeProperties(Model $model)
    {
        return Arr::where($model->getAttributes(), function ($value, $key) use ($model) {

            foreach ($model->mediaCollections as $mediaCollections) {
                if (strpos($key, $mediaCollections->name) === 0) {
                    return true;
                }
            }

            return false;
        });
    }

    private function handleMediables(Model $model)
    {
        foreach ($model->mediables as $mediable => $value) {
            foreach ($model->mediaCollections as $mediaCollection) {

                if ($mediaCollection->name === $mediable && $mediaCollection->singleFile) {
                    $this->handleSingleMedia($model, $value, $mediable);
                    break;
                } elseif ($mediaCollection->name === $mediable && !$mediaCollection->singleFile) {
                    $this->handleMultipleMedia($model, $value, $mediable);
                    break;
                }
            }
        }
    }

    private function handleSingleMedia(Model $model, $id, $collection)
    {
        if (!$id) {
            $model->getFirstMedia($collection)->delete();
            return;
        }

        $media = Media::where('model_type', MediaLibrary::class)->where('id', $id)->first();

        if ($media) {
            $media->move($model, $collection);
        }

    }

    private function handleMultipleMedia(Model $model, $ids, $collection)
    {
        if (!is_array($ids)) {
            abort(404);
        }

        if (isset($ids['delete'])) {
            $this->handleDeleteMultipleMedia($model, $ids['delete']);
            return;
        }

        if (isset($ids['add'])) {
            $this->handleAddMultipleMedia($model, $ids['add'], $collection);
            return;
        }

        Media::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->whereNotIn('id', $ids)
            ->delete();

        $media = Media::where('model_type', MediaLibrary::class)->whereIn('id', $ids);

        foreach ($media->get() as $item) {

            $newMedia = $item->move($model, $collection);

            if (($key = array_search($item->id, $ids)) !== false) {
                $ids[$key] = $newMedia->id;
            }
        }

        Media::setNewOrder($ids);

    }

    private function handleDeleteMultipleMedia(Model $model, $ids)
    {
        Media::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->whereIn('id', $ids)
            ->delete();
    }

    private function handleAddMultipleMedia(Model $model, $ids, $collection)
    {
        foreach ($ids as $id) {

            $media = Media::where('id', $id)->first();

            if ($media) {
                $media->move($model, $collection);
            }
        }
    }

}