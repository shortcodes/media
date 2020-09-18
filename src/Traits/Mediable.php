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

    public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        $media = $this->getFirstMedia($collectionName);

        if (!$media && $this->shouldRegenerate($collectionName, $conversionName)) {
            return $this->getFallbackMediaUrl($collectionName, $conversionName) ?: '';
        }

        if (!$media) {
            return '';
        }

        return $media->getUrl($conversionName);
    }

    private function shouldRegenerate($collectionName, $conversionName)
    {
        return $this->retringRegenerate && (
                $this->retringRegenerate === 'all' ||
                in_array($collectionName . '.' . $conversionName, $this->retringRegenerate)
            );
    }

    public function getFallbackMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        return route('media-retry', ['model' => self::class, 'id' => $this->id, 'collectionName' => $collectionName, 'conversionName' => $conversionName]);
    }

}

