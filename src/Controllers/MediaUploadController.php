<?php

namespace Shortcodes\Media\Controllers;

use App\Http\Controllers\Controller;
use Shortcodes\Media\Models\MediaLibrary;
use Shortcodes\Media\Requests\FileUploadRequest;
use Shortcodes\Media\Requests\ManipulateUploadRequest;
use Shortcodes\Media\Resources\MediaLibraryResource;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;

class MediaUploadController extends Controller
{
    private $manipulations;

    public function __construct()
    {
        $this->manipulations = [
            '*' => []
        ];

        $this->middleware('auth:api')->only('update');
    }

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

    public function update(Media $media, ManipulateUploadRequest $request)
    {
        if ($cropManipulation = collect($request->manipulations)->where('type', 'crop')->first()) {
            $this->cropManipulation($cropManipulation);
        }

        if (count($this->manipulations['*'])) {
            $media->manipulations = $this->manipulations;
        }

        $media->save();

        return $media->getFullUrl();
    }

    private function cropManipulation($cropManipulation)
    {
        $resizedConversion = Manipulations::create()->manualCrop(
            $cropManipulation['width'],
            $cropManipulation['height'],
            $cropManipulation['x'],
            $cropManipulation['y']
        );

        $this->manipulations['*'] = array_merge($this->manipulations['*'], $resizedConversion->toArray());
    }
}

