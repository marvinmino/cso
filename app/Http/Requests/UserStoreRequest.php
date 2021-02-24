<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password'  =>   'required|min:6|max:32|required_with:confirm_password',
            'confirm_password' => 'required_with:password|same:password',
            'username'     =>   'required|min:3|max:255',
            'email'        =>   'required|min:3|max:255|email',
        ];
    }
}
