<?php

namespace Shortcodes\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Intervention\Image\Exception\NotReadableException;
use Spatie\Image\Image;

class ManipulateUploadRequest extends FormRequest
{
    public function rules()
    {
        $media = $this->route('media');
        $image = Image::load($media->getPath());

        try {
            $width = $image->getWidth();
            $height = $image->getHeight();
        } catch (NotReadableException $exception) {
            abort(422, $exception->getMessage());
        }


        return [
            'manipulations.*.type' => 'required|in:crop',
            'manipulations.*.x' => 'required_if:manipulations.*.type,crop|gte:0|lt:' . $width,
            'manipulations.*.y' => 'required_if:manipulations.*.type,crop|gte:0|lt:' . $height,
            'manipulations.*.width' => 'required_if:manipulations.*.type,crop|gt:0|lt:' . $width,
            'manipulations.*.height' => 'required_if:manipulations.*.type,crop|gt:0|lt:' . $height,
        ];
    }
}
