<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ChangePasswordPostRequest extends Request
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
            'old' => 'required|currentPasswordMatches',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ];
    }

    /**
     * Custom error messages
     * @return array
     */
    public function messages()
    {
        return [
            'old.required' => 'The current password field is required',
            'password.required'  => 'The new password field is required',
            'password.min' => 'The new password field must be at least 6 characters',
            'password_confirmation.required'  => 'The new password confirmation field is required',
            'password.confirmed' => 'The new password and confirm password fields do not match',
        ];
    }
}
