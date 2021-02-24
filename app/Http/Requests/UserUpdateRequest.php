<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username'     =>   'required|min:3|max:255',
            'email'        =>   'required|min:3|max:255|email',
        ];
    }
}
