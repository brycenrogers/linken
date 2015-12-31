<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Hash;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Validate and update the User's password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        try {
            $this->validate($request, [
                '_token' => 'required',
                'old' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        $credentials = $request->only('old', 'password');

        // Verify old password matches
        if ( ! Hash::check($credentials['old'], $user->password)) {
            return response()->json(['error' => 'Current Password is not correct']);
        }

        // Update the user's password
        $user->password = Hash::make($credentials['password']);
        $user->save();

        return response()->json(['message' => 'success']);
    }
}