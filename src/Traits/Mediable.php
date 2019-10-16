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

    public static function isVideoMime($mimeType)
    {
        $videoMimes = config('medialibrary.video_mimetypes');

        if (!$videoMimes) {
            return false;
        }

        return in_array($mimeType, $videoMimes);
    }

    public function getFirstMediaUrlOrNull(string $collectionName = 'default', string $conversionName = '')
    {
        $return = $this->getFirstMediaUrl($collectionName, $conversionName);

        return $return ? $return : null;
    }
}

