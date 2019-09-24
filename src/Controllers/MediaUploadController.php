<?php

namespace Shortcodes\Media\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Shortcodes\Media\Models\MediaLibrary;
use Shortcodes\Media\Resources\MediaLibraryResource;

class MediaUploadController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file'
        ]);

        $mediaLibrary = new MediaLibrary();
        $media = $mediaLibrary->addMedia($request->file('file'))->toMediaCollection();


        return new MediaLibraryResource($media);

    }


}

