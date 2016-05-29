<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Redirect;
use Session;
use SocialAuth;
use App\Models\User;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;

/**
 * Class OAuthController
 * @package App\Http\Controllers
 */
class OAuthController extends Controller
{
    /**
     * Hands authentication off to provider using SocialAuth
     * @param $provider
     * @return mixed
     */
    public function auth($provider)
    {
        return SocialAuth::authorize($provider);
    }

    /**
     * Attempt login for provider and update user details
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login($provider)
    {
        try {
            SocialAuth::login($provider, function(User $user, $details) {
                // Update user details
                $user->email = $details->email;
                $user->user_photo = $details->avatar;
                if ($user->discovery_setting == '') {
                    $user->discovery_setting = 'attributed';
                }
                // Determine name
                if ($details->full_name) {
                    $name = $details->full_name;
                } elseif ($details->nickname) {
                    $name = $details->nickname;
                } elseif (isset($details->given_name)) {
                    $name = $details->given_name;
                } else {
                    $name = $details->email;
                }
                // Set the name
                $user->name = $name;
                // Save
                $user->save();
            });
        } catch (ApplicationRejectedException $e) {
            // User rejected application

        } catch (InvalidAuthorizationCodeException $e) {
            // Authorization was attempted with invalid
            // code,likely forgery attempt
        } catch (QueryException $e) {
            // User info update/save failed, likely because the email address already existed for another provider
            Session::flash('error', 'Could not authenticate. Your email may already be used by another account.');
            return Redirect::to('/');
        }

        return Redirect::intended();
    }
}
