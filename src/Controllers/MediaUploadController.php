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

            $mediaAsset = $request->get('url') ? $model->addMediaFromUrl($request->get('url')) : $model->addMedia($request->file('file'));

            $media = $mediaAsset->toMediaCollection($request->get('model_collection', 'default'));

            return new MediaLibraryResource($media);
        }

        $mediaLibrary = new MediaLibrary();

        $mediaAsset = $request->get('url') ? $mediaLibrary->addMediaFromUrl($request->get('url')) : $mediaLibrary->addMedia($request->file('file'));

        $media = $mediaAsset->toMediaCollection();

        return new MediaLibraryResource($media);

    }


}

