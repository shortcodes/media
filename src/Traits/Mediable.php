<?php

namespace Shortcodes\Media\Traits;

use Shortcodes\Media\Observers\MediableObserver;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Mediable
{
    use HasMediaTrait;

    public $mediables = [];

    public function initializeMediable()
    {
        if (!$this->mediaCollections) {
            $this->registerMediaCollections();
        }

        $collectionsName = array_map(function ($item) {
            return $item->name;
        }, $this->mediaCollections);

        $this->fillable = array_merge($this->fillable, $collectionsName);
    }

    public static function bootMediable()
    {
        static::observe(MediableObserver::class);
    }

//    public function setAttribute($key, $value)
//    {
//        if (!$this->mediaCollections) {
//            $this->registerMediaCollections();
//        }
//
//        $mediaCollections = collect($this->mediaCollections)->map(function ($item) {
//            return $item->name;
//        })->toArray();
//
//        $mediaMultipleCollections = collect($this->mediaCollections)->reject(function ($item) {
//            return $item->singleFile === true;
//        })->map(function ($item) {
//            return $item->name;
//        })->toArray();
//
//        if (in_array($key, $mediaCollections)) {
//
//            $items = [];
//
//            if (!in_array($key, $mediaMultipleCollections)) {
//                $items[] = ['url' => $value];
//            }
//
//            if (!in_array($key, $mediaMultipleCollections) && $value === null && $this->getFirstMedia($key)) {
//                $this->getFirstMedia($key)->delete();
//                return;
//            }
//
//            if (!$items) {
//                $items = $value;
//            }
//
//            foreach ($items as $item) {
//
//                if (strpos($item['url'], '/tmp/') !== false) {
//                    $filePath = ltrim(strstr($item['url'], '/tmp/'), '/');
//                    $storageFile = storage_path('app/' . $filePath);
//
//                    $media = $this->addMedia($storageFile);
//
//                    if ($this->isVideo($storageFile)) {
//                        $media->withCustomProperties(['isVideo' => true]);
//                    }
//
//                    if (isset($item['title'])) {
//                        $media->usingName($item['title']);
//                    }
//
//                    $media->toMediaCollection($key);
//                }
//            }
//
//            return;
//        }
//
//        return parent::setAttribute($key, $value);
//    }
//
//    private function isVideo($filePath)
//    {
//        $videoMimes = config('upload.video_mimetypes');
//
//        if (!$videoMimes) {
//            return false;
//        }
//
//        $type = File::mimeType($filePath);
//
//        return in_array($type, $videoMimes);
//    }
}

