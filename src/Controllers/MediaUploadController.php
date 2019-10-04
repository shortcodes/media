<?php

namespace Shortcodes\Media\Controllers;

use App\Http\Controllers\Controller;
use Shortcodes\Media\Models\MediaLibrary;
use Shortcodes\Media\Requests\FileUploadRequest;
use Shortcodes\Media\Resources\MediaLibraryResource;

class MediaUploadController extends Controller
{
    public function store(FileUploadRequest $request)
    {
        if ($modelType = $request->get('model_type')) {

            $model = $modelType::find($request->get('model_id'));

            $media = $model->addMedia($request->file('file'))->toMediaCollection($request->get('model_collection', 'default'));
            return new MediaLibraryResource($media);
        }

        $mediaLibrary = new MediaLibrary();
        $media = $mediaLibrary->addMedia($request->file('file'))->toMediaCollection();

        return new MediaLibraryResource($media);

    }


}

