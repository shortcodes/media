<?php

namespace Shortcodes\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Shortcodes\Media\Traits\Mediable;

class FileUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'url' => 'required_without:file|url|active_url',
            'file' => 'required_without:url|file',
            'model_type' => 'string',
            'model_id' => 'required_with:model_type',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $model = $this->get('model_type');

            if ($model && !in_array(Mediable::class, class_uses($model))) {
                $validator->errors()->add('model_type', trans('messages.model_type_must_use_trait_mediable'));
                return;
            }

            if ($model && !$model::find($this->get('model_id'))) {
                $validator->errors()->add('model_id', trans('messages.object_of_provided_id_does_not_exists'));
            }
        });
    }
}