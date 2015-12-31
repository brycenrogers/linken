<?php

namespace App\Http\Controllers;

use App\Interfaces\ImageHandlerInterface;
use Auth;
use Response;
use Illuminate\Http\Request;

class UserPhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle user photo uploads
     *
     * @param Request $request
     * @param ImageHandlerInterface $imageHandler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request, ImageHandlerInterface $imageHandler)
    {
        $user = Auth::user();
        $croppedPhotoData = $request->input('photoDataURI');
        $upload = $imageHandler->uploadUserPhoto($user, $croppedPhotoData);

        // @todo Add validation to prevent any kind of injection

        $request->session()->flash($upload['flash'], $upload['message']);

        if ($request->has('redirectTo')) {
            return Response::redirectTo($request->input('redirectTo'));
        }

        return Response::redirectTo('/');
    }
}