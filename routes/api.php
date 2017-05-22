<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('prefix' => 'rets/v1'), function () {
    //Route::controller('rets/v1','APIController');
    Route::resource('/', 'APIController');
    Route::get('homepage_listing', 'APIController@homepage_listing');
    Route::get('advance_search', 'APIController@advance_search');
    Route::get('property_desc/{matrix_unique_id}', 'APIController@property_desc');
    Route::get('photo_gallery/{matrix_unique_id}', 'APIController@photo_gallery');
    Route::get('address_search/', 'APIController@addresssearch');
    Route::get('advance_listing/', 'APIController@advance_listing');
    Route::get('mortgage_cal/{matrix_unique_id}', 'APIController@mortgage_calculator');
    Route::get('printable_flyer/{matrix_unique_id}', 'APIController@printable_flyer');
});