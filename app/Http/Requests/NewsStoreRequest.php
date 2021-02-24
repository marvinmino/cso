<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class NewsStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            'content'  => 'required'
        ];
    }
}
