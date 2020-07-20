<?php

namespace Shortcodes\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Shortcodes\Media\Traits\Mediable;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class MediaLibrary extends Model implements HasMedia
{
    use Mediable;

    public $exists = true;

    protected $table = 'media';
}
