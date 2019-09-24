<?php

namespace Shortcodes\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class MediaLibrary extends Model implements HasMedia
{
    use HasMediaTrait;

    public $exists = true;

    public static function isVideoMime($mimeType)
    {
        $videoMimes = config('medialibrary.video_mimetypes');

        if (!$videoMimes) {
            return false;
        }

        return in_array($mimeType, $videoMimes);
    }

}
