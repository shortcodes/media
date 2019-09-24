<?php

namespace Shortcodes\Media\Observers;

use App\Models\News;
use Auth;
use Shortcodes\Media\Models\MediaLibrary;
use Spatie\MediaLibrary\Models\Media;

class MediaObserver
{
    public function creating(Media $media)
    {
        if (!$media->model_id) {
            $media->model_type = MediaLibrary::class;
            $media->model_id = 1;
        }
    }
}