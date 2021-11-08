<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['user_create','user_edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => [
                'string',
                'required',
            ],
            'email'         => [
                'sometimes',
                'email',
                'required',
                'unique:users,email,' . request()->id,
            ],
            'password'      => [
                'sometimes',
                'confirmed',
                // 'min:6',
            ],
        ];
    }
}
