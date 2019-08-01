<?php

namespace Shortcodes\Media\Traits;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Mediable
{
    use HasMediaTrait;

    public function getFirstMediaUrlOrNull(string $collectionName = 'default', string $conversionName = '')
    {
        $return = $this->getFirstMediaUrl($collectionName, $conversionName);

        return $return ? $return : null;
    }

    public function setAttribute($key, $value)
    {
        if (!$this->mediaCollections) {
            $this->registerMediaCollections();
        }

        $mediaCollections = collect($this->mediaCollections)->map(function ($item) {
            return $item->name;
        })->toArray();


        if (in_array($key, $mediaCollections)) {

            if ($value === null && $this->getFirstMedia($key)) {
                $this->getFirstMedia($key)->delete();
            }

            if (strpos($value, '/tmp/') !== false) {
                $filePath = ltrim(strstr($value, '/tmp/'), '/');
                $storageFile = storage_path('app/' . $filePath);

                $this->addMedia($storageFile)->toMediaCollection($key);
            }
        }

        return parent::setAttribute($key, $value);
    }
}

