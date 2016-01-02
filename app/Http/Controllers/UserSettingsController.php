<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordPostRequest;
use Auth;
use Hash;
use Validator;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Add a custom validation rule for checking the user's current password
        Validator::extend('currentPasswordMatches', function($attribute, $value, $parameters, $validator) {
            return Hash::check($value, Auth::user()->password);
        }, 'The current password is incorrect');
    }

    /**
     * Validate and update the User's password
     *
     * @param ChangePasswordPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordPostRequest $request)
    {
        // Update the user's password
        $credentials = $request->only('old', 'password');
        $user = Auth::user();
        $user->password = Hash::make($credentials['password']);
        $user->save();

        return response()->json(['message' => 'success']);
    }
}