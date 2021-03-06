<?php

namespace App\Http\Requests;

use Auth;

class AddItemPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '_token' => 'required',
            'value' => 'required',
            'type' => 'required|in:Link,Note'
        ];
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'value.required' => 'A link or note is required',
            'type.required'  => 'Error determining type of content',
            'type.in'  => 'Content must be either a Link or a Note',
        ];
    }
}
