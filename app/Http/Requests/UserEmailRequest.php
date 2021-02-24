<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class UserEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|min:3|max:255',
        ];
    }
}
