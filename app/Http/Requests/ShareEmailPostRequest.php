<?php

namespace App\Http\Requests;

use Auth;

class ShareEmailPostRequest extends Request
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
        $rules = [
            '_token' => 'required'
        ];

        foreach($this->request->get('emails') as $key => $val) {
            $rules['emails.'.$key] = 'required|email';
        }

        return $rules;
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach($this->request->get('emails') as $key => $val) {
            $messages['emails.'.$key.'.email'] = 'The email address "' . $val . '" is invalid.';
        }
        return $messages;
    }
}
