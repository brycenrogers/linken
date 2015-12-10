<?php

namespace App\Http\Controllers;

use Auth;
use Input;
use Validator;
use Response;
use File;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postUpload(Request $request)
    {
        $user = Auth::user();

        $croppedPhotoData = $request->input('photoDataURI');
        $data = file_get_contents($croppedPhotoData);

        if ($data) {
            $destinationPath = public_path() .'/uploads/' . $user->id . ".png";
            if(file_put_contents($destinationPath, $data)) {
                $request->session()->flash('success', 'Photo updated!');
            } else {
                $request->session()->flash('error', 'Photo could not be updated :(');
            }
        }

        if ($request->has('redirectTo')) {
            return Response::redirectTo($request->input('redirectTo'));
        }

        return Response::redirectTo('/');
    }
}