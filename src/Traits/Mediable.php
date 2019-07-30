<?php

namespace Shortcodes\Media\Traits;

use Shortcodes\Media\Observers\MediableObserver;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Mediable
{
    use HasMediaTrait;

    public static function bootMediable()
    {
        static::observe(MediableObserver::class);
    }
}

