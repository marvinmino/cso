<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class ImageStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'string|min:1|max:40',
            'image' => 'mimes:jpeg,png|max:5000',
        ];
    }
}
