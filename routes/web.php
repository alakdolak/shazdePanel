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

Route::group(array('middleware' => ['auth', 'adminLevel']), function() {

    Route::get('choosePlace/{mode}', ['as' => 'choosePlace', 'uses' => 'PlaceController@choosePlace']);

    Route::get('chooseCity/{mode}', ['as' => 'chooseCity', 'uses' => 'PlaceController@chooseCity']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'altAccess']), function () {

    Route::get('changeAlt/{id}/{mode}', ['as' => 'changeAlt', 'uses' => 'AltController@changeAlt']);

    Route::post('doChangeAlt/{id}/{kindPlaceId}', ['as' => 'doChangeAlt', 'uses' => 'AltController@doChangeAlt']);

    Route::post('doChangeAltForUserPic', ['as' => 'doChangeAltForUserPic', 'uses' => 'AltController@doChangeAltForUserPic']);

    Route::post('doChangePic/{id}/{kindPlaceId}', ['as' => 'doChangePic', 'uses' => 'AltController@doChangePic']);

    Route::post('removeMainPic/{id}/{kindPlaceId}', ['as' => 'removeMainPic', 'uses' => 'AltController@removeMainPic']);

    Route::post('removeUserPic', ['as' => 'removeUserPic', 'uses' => 'AltController@removeUserPic']);

    Route::post('doChangeUserPic', ['as' => 'doChangeUserPic', 'uses' => 'AltController@doChangeUserPic']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'seoAccess']), function () {

    Route::get('changeSeo/{city}', ['as' => 'changeSeo', 'uses' => 'SeoController@changeSeo']);

    Route::post('doChangeSeo', ['as' => 'doChangeSeo', 'uses' => 'SeoController@doChangeSeo']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'contentAccess']), function () {

    Route::get('changeContent/{city}/{mode}', ['as' => 'changeContent', 'uses' => 'PlaceController@changeContent']);

    Route::post('doChangePlace', ['as' => 'doChangePlace', 'uses' => 'PlaceController@doChangePlace']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'publicityAccess']), function () {

    Route::any('addCompany' , array('as' => 'company' , 'uses' => 'PublicityController@addCompany'));

    Route::post('deleteCompany' , array('as' => 'deleteCompany' , 'uses' => 'PublicityController@deleteCompany'));

    Route::any('addSection' , array('as' => 'section' , 'uses' => 'PublicityController@addSection'));

    Route::post('deleteSection' , array('as' => 'deleteSection' , 'uses' => 'PublicityController@deleteSection'));

    Route::any('seeAds', array('as' => 'seeAds', 'uses' => 'PublicityController@seeAds'));

    Route::any('addAds', array('as' => 'addAds', 'uses' => 'PublicityController@addAds'));

    Route::any('editAd/{adId}', array('as' => 'editAd', 'uses' => 'PublicityController@editAd'));

    Route::post('deleteAd', array('as' => 'deleteAd', 'uses' => 'PublicityController@deleteAd'));

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'postAccess']), function () {

    Route::get('posts', ['as' => 'posts', 'uses' => 'PostController@posts']);


});

Route::group(array('middleware' => ['auth', 'adminLevel', 'offCodeAccess']), function () {

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'commentAccess']), function () {

    Route::get('lastActivities', ['as' => 'lastActivities', 'uses' => 'CommentController@lastActivities']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'configAccess']), function () {

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

    Route::get('backup', ['as' => 'backup', 'uses' => 'BackupController@backup']);

    Route::get('manualBackup', ['as' => 'manualBackup', 'uses' => 'BackupController@manualBackup']);

    Route::post('addBackup', ['as' => 'addBackup', 'uses' => 'BackupController@addBackup']);

    Route::post('removeBackup', ['as' => 'removeBackup', 'uses' => 'BackupController@removeBackup']);
});


Route::group(array('middleware' => ['auth', 'superAdminLevel']), function () {

    Route::get('users', ['as' => 'users', 'uses' => 'UserController@users']);

    Route::get('register', ['as' => 'register', 'uses' => 'UserController@register']);

    Route::get('manageAccess/{userId}', ['as' => 'manageAccess', 'uses' => 'UserController@manageAccess']);

    Route::post('changeAccess', ['as' => 'changeAccess', 'uses' => 'UserController@changeAccess']);

    Route::post('toggleStatusUser', ['as' => 'toggleStatusUser', 'uses' => 'UserController@toggleStatusUser']);

    Route::post('addAdmin', ['as' => 'addAdmin', 'uses' => 'UserController@addAdmin']);

});

Route::group(array('middleware' => ['auth']), function () {

    Route::get('/', ['as' => 'root', 'uses' => 'HomeController@home']);

    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@home']);

    Route::post('totalSearch', array('as' => 'totalSearch', 'uses' => 'HomeController@totalSearch'));

    Route::post('searchForCity', array('as' => 'searchForCity', 'uses' => 'PlaceController@searchForCity'));

    Route::get('logout', ['as' => 'logout', 'uses' => 'HomeController@logout']);

});