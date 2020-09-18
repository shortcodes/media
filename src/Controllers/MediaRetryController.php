<?php

namespace Shortcodes\Media\Controllers;

use App\Http\Controllers\Controller;

class MediaRetryController extends Controller
{
    public function show()
    {
        return optional(request()->get('model')::findOrFail(request('id'))
            ->getFirstMedia(request('collectionName')))
            ->getUrl(request('conversionName'));
    }
}

