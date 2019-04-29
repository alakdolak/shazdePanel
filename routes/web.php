<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('login', ['as' => 'login', 'uses' => 'HomeController@login']);

Route::post('doLogin', ['as' => 'doLogin', 'uses' => 'HomeController@doLogin']);

Route::group(array('middleware' => ['auth']), function () {

    Route::get('changeAlt/{id}/{mode}', ['as' => 'changeAlt', 'uses' => 'AltController@changeAlt']);

    Route::post('doChangeAlt/{id}/{kindPlaceId}', ['as' => 'doChangeAlt', 'uses' => 'AltController@doChangeAlt']);

    Route::get('changeSeo/{city}', ['as' => 'changeSeo', 'uses' => 'SeoController@changeSeo']);

    Route::post('doChangeSeo', ['as' => 'doChangeSeo', 'uses' => 'SeoController@doChangeSeo']);

    Route::get('choosePlace/{mode}', ['as' => 'choosePlace', 'uses' => 'PlaceController@choosePlace']);

    Route::get('chooseCity/{mode}', ['as' => 'chooseCity', 'uses' => 'PlaceController@chooseCity']);

    Route::get('changeContent/{city}/{mode}', ['as' => 'changeContent', 'uses' => 'PlaceController@changeContent']);

    Route::post('doChangePlace', ['as' => 'doChangePlace', 'uses' => 'PlaceController@doChangePlace']);

    Route::post('doChangePic/{id}/{kindPlaceId}', ['as' => 'doChangePic', 'uses' => 'AltController@doChangePic']);

    Route::post('removeMainPic/{id}/{kindPlaceId}', ['as' => 'removeMainPic', 'uses' => 'AltController@removeMainPic']);

});

Route::group(array('middleware' => ['auth']), function () {

    Route::get('/', ['as' => 'root', 'uses' => 'HomeController@home']);

    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@home']);

    Route::any('medals', array('as' => 'medals', 'uses' => 'MedalController@showMedals'));

    Route::any('addMedal', array('as' => 'addMedal', 'uses' => 'MedalController@addMedal'));

    Route::post('opOnMedal', array('as' => 'opOnMedal', 'uses' => 'MedalController@opOnMedal'));

    Route::any('levels', array('as' => 'levels', 'uses' => 'LevelController@showLevels'));

    Route::any('addLevel', array('as' => 'addLevel', 'uses' => 'LevelController@addLevel'));

    Route::post('opOnLevel', array('as' => 'opOnLevel', 'uses' => 'LevelController@opOnLevel'));

    Route::any('determineRadius', array('as' => 'determineRadius', 'uses' => 'HomeController@determineRadius'));
    
    Route::any('ages', array('as' => 'ages', 'uses' => 'AgeController@ages'));
    
    Route::any('addAge', array('as' => 'addAge', 'uses' => 'AgeController@addAge'));
    
    Route::post('opOnAge', array('as' => 'opOnAge', 'uses' => 'AgeController@opOnAge'));

    Route::post('totalSearch', array('as' => 'totalSearch', 'uses' => 'HomeController@totalSearch'));

    Route::post('searchForCity', array('as' => 'searchForCity', 'uses' => 'PlaceController@searchForCity'));

    Route::get('logout', ['as' => 'logout', 'uses' => 'HomeController@logout']);

});