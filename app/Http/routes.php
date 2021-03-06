<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
use App\Models\User;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/success', 'Auth\AuthController@success');

// Social Login Providers
Route::get('oauth/{provider}/auth', 'Auth\OAuthController@auth');
Route::get('oauth/{provider}/login', 'Auth\OAuthController@login');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::post('settings/userSettings', 'UserSettingsController@userSettings');
Route::post('settings/changePassword', 'UserSettingsController@changePassword');
Route::post('user/photo/upload', 'UserPhotoController@upload');
Route::post('link/parse', 'LinkParseController@postLinkParse');
Route::post('item/add', 'ItemController@add');
Route::post('item/update', 'ItemController@update');
Route::post('item/email', 'ItemController@email');

Route::get('test', 'MainController@test');
Route::get('item/destroy/{id}', 'ItemController@destroy');
Route::get('search/reindex', 'SearchController@reindex');
Route::get('search', 'SearchController@search');
Route::get('discover', 'DiscoverController@discover');
Route::get('tags/pane', 'TagController@getTagsPane');
Route::get('tags/search', 'TagController@search');
Route::get('tags', 'TagController@findItemsForTags');
Route::get('help', 'HelpController@index');
Route::get('home', 'MainController@getAll');
Route::get('/', 'MainController@getAll');