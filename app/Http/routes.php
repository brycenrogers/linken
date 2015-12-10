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
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::post('photo/upload', 'PhotoController@postUpload');
Route::post('link/parse', 'LinkParseController@postLinkParse');
Route::post('item/store', 'ItemController@store');
Route::get('item/destroy/{id}', 'ItemController@destroy');
Route::get('search/reindex', 'SearchController@reindex');
Route::get('search', 'SearchController@search');
Route::get('tags/pane', 'TagController@getTagsPane');
Route::get('tags/search', 'TagController@search');
Route::get('tags', 'ItemController@findItemsForTags');

Route::get('/', 'MainController@getAll');