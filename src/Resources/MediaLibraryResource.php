<?php

namespace Shortcodes\Media\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Shortcodes\Media\Models\MediaLibrary;

class MediaLibraryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size' => $this->size,
            'is_video' => $this->when(MediaLibrary::isVideoMime($this->mime_type), true),
            'url' => $this->getFullUrl($this->conversion ?? '')
        ];
    }
}