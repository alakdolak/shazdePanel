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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Request;


Route::get('updateMainDataBase', function(){

});


Route::get('login', ['as' => 'login', 'uses' => 'HomeController@login']);

Route::post('doLogin', ['as' => 'doLogin', 'uses' => 'HomeController@doLogin']);

Route::group(array('middleware' => ['auth', 'adminLevel']), function () {

//    فعالیت
    Route::get('activities', 'ActivitiesController@index')->name('activities.index');
    Route::post('activities/store', 'ActivitiesController@store')->name('activities.store');
    Route::post('activities/doEdit', 'ActivitiesController@doEdit')->name('activities.doEdit');
    Route::post('activities/delete', 'ActivitiesController@delete')->name('activities.delete');

//    تصاویر پیش قرض
    Route::get('defaultPics', 'DefaultPicsController@index')->name('defaultPics.index');
    Route::post('defaultPics/store', 'DefaultPicsController@store')->name('defaultPics.store');
    Route::post('defaultPics/doEdit', 'DefaultPicsController@doEdit')->name('defaultPics.doEdit');
    Route::post('defaultPics/delete', 'DefaultPicsController@delete')->name('defaultPics.delete');

//    اماکن
    Route::get('places', 'PlaceController@index')->name('places.index');
    Route::post('places/store', 'PlaceController@store')->name('places.store');
    Route::post('places/doEdit', 'PlaceController@doEdit')->name('places.doEdit');
    Route::post('places/delete', 'PlaceController@delete')->name('places.delete');

//    سبک سفر
    Route::get('tripStyle', 'TripStyleController@index')->name('tripStyle.index');
    Route::post('tripStyle/store', 'TripStyleController@store')->name('tripStyle.store');
    Route::post('tripStyle/doEdit', 'TripStyleController@doEdit')->name('tripStyle.doEdit');
    Route::post('tripStyle/delete', 'TripStyleController@delete')->name('tripStyle.delete');

//    تگ ها
    Route::get('tags/{kind?}', 'TagsController@index')->name('tags.index');
    Route::post('tags/store', 'TagsController@store')->name('tags.store');
    Route::post('tags/doEdit', 'TagsController@doEdit')->name('tags.doEdit');
    Route::post('tags/delete', 'TagsController@delete')->name('tags.delete');

//    تسبک مکان
    Route::get('placeStyle/{kind?}', 'PlaceStyleController@index')->name('placeStyle.index');
    Route::post('placeStyle/store', 'PlaceStyleController@store')->name('placeStyle.store');
    Route::post('placeStyle/doEdit', 'PlaceStyleController@doEdit')->name('placeStyle.doEdit');
    Route::post('placeStyle/delete', 'PlaceStyleController@delete')->name('placeStyle.delete');

//    ایتم تصاویر
    Route::get('picItems/{kind?}', 'PicItemController@index')->name('picItems.index');
    Route::post('picItems/store', 'PicItemController@store')->name('picItems.store');
    Route::post('picItems/doEdit', 'PicItemController@doEdit')->name('picItems.doEdit');
    Route::post('picItems/delete', 'PicItemController@delete')->name('picItems.delete');

//  سئولات نظرسنجی
    Route::get('questions', 'QuestionsController@index')->name('questions.index');
    Route::get('questions/new', 'QuestionsController@newPage')->name('questions.new');
    Route::get('questions/edit/{id}', 'QuestionsController@editPage')->name('questions.edit');
    Route::post('questions/store', 'QuestionsController@store')->name('questions.store');
    Route::post('questions/doEdit', 'QuestionsController@doEdit')->name('questions.doEdit');
    Route::post('questions/delete', 'QuestionsController@delete')->name('questions.delete');

//  گزارشات
    Route::get('reports/{kind?}', 'ReportsController@index')->name('reports.index');
    Route::post('reports/store', 'ReportsController@store')->name('reports.store');
    Route::post('reports/doEdit', 'ReportsController@doEdit')->name('reports.doEdit');
    Route::post('reports/delete', 'ReportsController@delete')->name('reports.delete');

    //    تگ گویش
    Route::get('goyeshTags', 'GoyeshTagsController@index')->name('goyeshTags.index');
    Route::post('goyeshTags/store', 'GoyeshTagsController@store')->name('goyeshTags.store');
    Route::post('goyeshTags/doEdit', 'GoyeshTagsController@doEdit')->name('goyeshTags.doEdit');
    Route::post('goyeshTags/delete', 'GoyeshTagsController@delete')->name('goyeshTags.delete');

    //    توضیحات سن برای بلیت
    Route::any('ageSentences', 'AgeController@ageSentences')->name('ageSentences');

    Route::get('topInCity/{cityId?}', 'PlaceController@topInCity')->name('topInCity');
    Route::post('topInCity/store', 'PlaceController@topInCityStore')->name('topInCity.store');
    Route::post('findInCity', 'PlaceController@findInCity')->name('findInCity');

    Route::get('choosePlace/{mode}', ['as' => 'choosePlace', 'uses' => 'PlaceController@choosePlace']);

    Route::get('chooseCity/{mode}', ['as' => 'chooseCity', 'uses' => 'PlaceController@chooseCity']);

    Route::get('city/index', ['as' => 'city.index', 'uses' => 'PlaceController@indexCity']);

    Route::get('city/add', ['as' => 'city.add', 'uses' => 'PlaceController@addCity']);

    Route::post('city/store', ['as' => 'city.store', 'uses' => 'PlaceController@storeCity']);

    Route::get('city/edit/{id}', ['as' => 'city.edit', 'uses' => 'PlaceController@editCity']);

    Route::post('city/doEdit', ['as' => 'city.doEdit', 'uses' => 'PlaceController@doEditCity']);

    Route::post('city/delete', ['as' => 'city.delete', 'uses' => 'PlaceController@deleteCity']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'seoAccess']), function () {

    Route::get('changeSeo/{city}/{mode}/{wantedKey?}/{selectedMode?}', ['as' => 'changeSeo', 'uses' => 'SeoController@changeSeo']);

    Route::post('doChangeSeo', ['as' => 'doChangeSeo', 'uses' => 'SeoController@doChangeSeo']);

    Route::get('manageNoFollow', ['as' => 'manageNoFollow', 'uses' => 'SeoController@manageNoFollow']);

    Route::get('seoTester', ['as' => 'seoTester', 'uses' => 'SeoController@seoTester']);

    Route::post('doSeoTest', ['as' => 'doSeoTest', 'uses' => 'SeoController@doSeoTest']);
    
    Route::post('changeNoFollow', ['as' => 'changeNoFollow', 'uses' => 'SeoController@changeNoFollow']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'contentAccess']), function () {

    Route::get('changeContent/{city}/{mode}/{cityMode}/{wantedKey?}/{filter?}', ['as' => 'changeContent', 'uses' => 'PlaceController@changeContent']);

    Route::get('newChangeContent/{cityId}/{mode}/{cityMode}', 'PlaceController@newChangeContent')->name('newChangeContent');

    Route::get('editContent/{mode}/{id}', 'PlaceController@editContent')->name('editContent');

    Route::get('newContent/{cityMode}/{cityId}/{mode}', 'PlaceController@newContent')->name('newContent');

    Route::post('storeAmaken', 'PlaceController@storeAmaken')->name('storeAmaken');

    Route::post('storeHotel', 'PlaceController@storeHotel')->name('storeHotel');

    Route::post('storeRestaurant', 'PlaceController@storeRestaurant')->name('storeRestaurant');

    Route::post('storeMajara', 'PlaceController@storeMajara')->name('storeMajara');

    Route::post('storeMahaliFood', 'PlaceController@storeMahaliFood')->name('storeMahaliFood');

    Route::post('storeSogatSanaie', 'PlaceController@storeSogatSanaie')->name('storeSogatSanaie');

    Route::get('uploadImgPage/{kindPlaceId}/{id}', 'PlaceController@uploadImgPage')->name('uploadImgPage');
    Route::post('getCrop', 'PlaceController@getCrop') ->name('getCrop');
    Route::post('deletePlacePic', 'PlaceController@deletePlacePic') ->name('deletePlacePic');
    Route::post('changeAltPic', 'PlaceController@changeAltPic') ->name('changeAltPic');
    Route::post('setMainPic', 'PlaceController@setMainPic') ->name('setMainPic');
    Route::post('deletePlace', 'PlaceController@deletePlace')->name('deletePlace');

    Route::post('doChangePlace', ['as' => 'doChangePlace', 'uses' => 'PlaceController@doChangePlace']);

    Route::get('uploadMainContent', ['as' => 'uploadMainContent', 'uses' => 'PlaceController@uploadMainContent']);

    Route::post('doUploadMainContent', ['as' => 'doUploadMainContent', 'uses' => 'PlaceController@doUploadMainContent']);

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'publicityAccess']), function () {

    Route::any('addCompany' , array('as' => 'company' , 'uses' => 'PublicityController@addCompany'));

    Route::post('deleteCompany' , array('as' => 'deleteCompany' , 'uses' => 'PublicityController@deleteCompany'));

    Route::any('addSection' , array('as' => 'section' , 'uses' => 'PublicityController@addSection'));

    Route::any('editSection' , array('as' => 'editSection' , 'uses' => 'PublicityController@editSection'));

    Route::post('editSection' , array('as' => 'editSection' , 'uses' => 'PublicityController@editSection'));

    Route::get('sectionStep2/{sectionId}' , array('as' => 'sectionStep2' , 'uses' => 'PublicityController@sectionStep2'));

    Route::post('addPageToSection/{sectionId}' , array('as' => 'addPageToSection' , 'uses' => 'PublicityController@addPageToSection'));
    
    Route::post('deleteSection' , array('as' => 'deleteSection' , 'uses' => 'PublicityController@deleteSection'));

    Route::any('seeAds', array('as' => 'seeAds', 'uses' => 'PublicityController@seeAds'));

    Route::any('addAds', array('as' => 'addAds', 'uses' => 'PublicityController@addAds'));

    Route::any('editAd/{adId}', array('as' => 'editAd', 'uses' => 'PublicityController@editAd'));

    Route::post('deleteAd', array('as' => 'deleteAd', 'uses' => 'PublicityController@deleteAd'));

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'postAccess']), function () {

    Route::group(array('middleware' => ['auth', 'superAdminLevel']), function (){
        Route::post('/post/category/delete', 'PostController@deleteCategory')->name('post.category.delete');

        Route::post('newPostCategory', 'PostController@newPostCategory')->name('newPostCategory');
    });

    Route::get('/gardeshNameList', 'PostController@gardeshNameList')->name('gardeshNameList');

    Route::get('posts', ['as' => 'posts', 'uses' => 'PostController@posts']);

    Route::get('createPost', ['as' => 'createPost', 'uses' => 'PostController@createPost']);

    Route::get('editPost/{id}', ['as' => 'editPost', 'uses' => 'PostController@editPost']);
    
    Route::post('/uploadCKEditor', function (Request $request) {

        try {

            if ($this->request->hasFiles() == true) {
                $errors = []; // Store all foreseen and unforseen errors here
                $fileExtensions = ['jpeg','jpg','png','gif','svg'];
                $uploadDirectory = __DIR__ . '/../../Uploads/';
                $Y = date("Y");
                $M = date("m");

                foreach ($this->request->getUploadedFiles() as $file) {

                    if (in_array($file->getExtension(),$fileExtensions)) {

                        if($file->getSize() < 2000000)  {
                            if (!file_exists($uploadDirectory . $Y)) {
                                mkdir($uploadDirectory.$Y, 0777, true);
                            }
                            if (!file_exists($uploadDirectory.$Y.'/'.$M)) {
                                mkdir($uploadDirectory.$Y.'/'.$M, 0777, true);
                            }

                            $namenew = md5($file->getName().time()).'.'.$file->getExtension();
                            $uploadDirectory .= $Y.'/'.$M.'/';
                            $file->moveTo($uploadDirectory.$namenew);
                        }
                        else {
                            $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
                        }
                    }
                    else{$errors[] = "This file extension is not allowed. Please upload a JPEG ,svg,gif,,jpg,PNG file";}

                    if(empty($errors))  {
                        echo '{
                        "uploaded": true,
                        "url": "http://localhost/cms/public/Uploads/'.$Y.'/'.$M.'/'.$namenew.'"}';
                    }
                    else{
                        echo '{
                        "uploaded": false,
                        "error": {
                            "message": "could not upload this image1"
                    }';
                    }
                }
            }
            else {
                echo '{
                "uploaded": false,
                "error": {
                    "message": "could not upload this image1"
                }';
            }
        }

        catch (\Exception $e) {
            echo '{
            "uploaded": false,
            "error": {
                "message": "could not upload this image0"
            }';
        }
    })->name('uploadCKEditor');

    Route::post('deletePost', ['as' => 'deletePost', 'uses' => 'PostController@deletePost']);

    Route::post('addToFavoritePosts', ['as' => 'addToFavoritePosts', 'uses' => 'PostController@addToFavoritePosts']);

    Route::post('addToBannerPosts', ['as' => 'addToBannerPosts', 'uses' => 'PostController@addToBannerPosts']);

    Route::post('deleteFromFavoritePosts', ['as' => 'deleteFromFavoritePosts', 'uses' => 'PostController@deleteFromFavoritePosts']);

    Route::post('deleteFromBannerPosts', ['as' => 'deleteFromBannerPosts', 'uses' => 'PostController@deleteFromBannerPosts']);

    Route::post('postTagSearch', 'PostController@postTagSearch')->name('postTagSearch');

    Route::post('storePost', 'PostController@storePost')->name('storePost');

    Route::post('storeDescriptionPost', 'PostController@storeDescriptionPost')->name('storeDescriptionPost');

    Route::post('imageUploadCKeditor4', 'PostController@imageUploadCKeditor4')->name('imageUploadCKeditor4');

    Route::post('seoTesterPostContent', 'SeoController@seoTesterPostContent')->name('seoTesterPostContent');

});

Route::group(array('middleware' => ['auth', 'adminLevel', 'msgAccess']), function () {

    Route::get('sendMsg', ['as' => 'sendMsg', 'uses' => 'MsgController@sendMsg']);

    Route::post('doSendMsg', ['as' => 'doSendMsg', 'uses' => 'MsgController@doSendMsg']);

    Route::get('msgs', ['as' => 'msgs', 'uses' => 'MsgController@msgs']);
    
});

Route::group(array('middleware' => ['auth', 'adminLevel', 'offCodeAccess']), function () {

    Route::get('offers/{mode?}', ['as' => 'offers', 'uses' => 'OffCodeController@offers']);

    Route::get('createOffer', ['as' => 'createOffer', 'uses' => 'OffCodeController@createOffer']);

    Route::post('doCreateOffer', ['as' => 'doCreateOffer', 'uses' => 'OffCodeController@doCreateOffer']);

    Route::post('deleteOffer', ['as' => 'deleteOffer', 'uses' => 'OffCodeController@deleteOffer']);
    
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

    Route::get('getImageBackup/{idx}', ['as' => 'getImageBackup', 'uses' => 'BackupController@getImageBackup']);
    
    Route::post('imageBackup', ['as' => 'imageBackup', 'uses' => 'BackupController@imageBackup']);

    Route::post('getDonePercentage', ['as' => 'getDonePercentage', 'uses' => 'BackupController@getDonePercentage']);

    Route::post('initialImageBackUp', ['as' => 'initialImageBackUp', 'uses' => 'BackupController@initialImageBackUp']);

});

Route::group(array('middleware' => ['auth', 'superAdminLevel']), function () {

    Route::get('users', ['as' => 'users', 'uses' => 'UserController@users']);

    Route::get('register', ['as' => 'register', 'uses' => 'UserController@register']);

    Route::get('manageAccess/{userId}', ['as' => 'manageAccess', 'uses' => 'UserController@manageAccess']);

    Route::post('changeAccess', ['as' => 'changeAccess', 'uses' => 'UserController@changeAccess']);

    Route::post('toggleStatusUser', ['as' => 'toggleStatusUser', 'uses' => 'UserController@toggleStatusUser']);

    Route::post('addAdmin', ['as' => 'addAdmin', 'uses' => 'UserController@addAdmin']);

    Route::post('changePass', ['as' => 'changePass', 'uses' => 'UserController@changePass']);

    Route::get('systemLastActivities', ['as' => 'systemLastActivities', 'uses' => 'LastActivityController@lastActivities']);

    Route::post('deleteSystemLogs', ['as' => 'deleteSystemLogs', 'uses' => 'LastActivityController@deleteLogs']);
});

Route::group(array('middleware' => ['auth']), function () {

    Route::get('/', ['as' => 'root', 'uses' => 'HomeController@home']);

    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@home']);

    Route::post('totalSearch', array('as' => 'totalSearch', 'uses' => 'HomeController@totalSearch'));

    Route::post('searchForCity', array('as' => 'searchForCity', 'uses' => 'PlaceController@searchForCity'));

    Route::post('searchForCityAndState', array('as' => 'searchForCityAndState', 'uses' => 'PlaceController@searchForCityAndState'));

    Route::post('searchForState', array('as' => 'searchForState', 'uses' => 'PlaceController@searchForState'));

    Route::get('logout', ['as' => 'logout', 'uses' => 'HomeController@logout']);

    Route::get('changePass', ['as' => 'changePass', 'uses' => 'HomeController@changePass']);

    Route::post('doChangePass', ['as' => 'doChangePass', 'uses' => 'HomeController@doChangePass']);

    Route::get('/photographer/Index', 'UserContentController@photographerIndex')->name('photographer.index');

    Route::post('/photographer/submit', 'UserContentController@photographerSubmit')->name('photographer.submit');

    Route::post('/photographer/delete', 'UserContentController@photographerDelete')->name('photographer.delete');

    Route::get('/reviews/index', 'ReviewsController@index')->name('reviews.index');

    Route::post('/reviews/pic/delete', 'ReviewsController@deleteReviewPic')->name('reviews.pic.delete');

    Route::post('/reviews/confirm', 'ReviewsController@confirmReview')->name('reviews.confirm');

    Route::post('/reviews/delete', 'ReviewsController@deleteReview')->name('reviews.delete');
});

//mainPage slider setting
Route::group(array('middleware' => ['auth']), function(){

    Route::get('/mainSlider/index', 'SliderController@index')->name('slider.index');

    Route::post('/mainSlider/picStore', 'SliderController@storePic')->name('slider.storePic');

    Route::post('/mainSlider/deletePic', 'SliderController@deletePic')->name('slider.deletePic');

    Route::post('/mainSlider/changePic', 'SliderController@changePic')->name('slider.changePic');

    Route::post('/mainSlider/changeAltPic', 'SliderController@changeAltPic')->name('slider.changeAltPic');

    Route::post('/mainSlider/changeTextPic', 'SliderController@changeTextPic')->name('slider.changeTextPic');

    Route::post('/mainSlider/changeColor', 'SliderController@changeColor')->name('slider.changeColor');
});

//mainPage Suggesion
Route::group(array('middleware' => ['auth']), function(){

    Route::get('/mainSuggestion/index', 'MainSuggestionController@index')->name('mainSuggestion.index');

    Route::post('/mainSuggestion/search', 'MainSuggestionController@search')->name('mainSuggestion.search');

    Route::post('/mainSuggestion/chooseId', 'MainSuggestionController@chooseId')->name('mainSuggestion.chooseId');

    Route::post('/mainSuggestion/deleteRecord', 'MainSuggestionController@deleteRecord')->name('mainSuggestion.deleteRecord');
});

//comments
Route::group(array('middleware' => ['auth', 'adminLevel', 'commentAccess']), function () {

    Route::get('/comments/new', 'CommentController@newComments')->name('comments.new');

    Route::post('/comments/delete', 'CommentController@deleteComment')->name('comment.delete');

    Route::post('/comments/submit', 'CommentController@submitComment')->name('comment.submit');

    Route::get('lastActivities', ['as' => 'lastActivities', 'uses' => 'CommentController@lastActivities']);

    Route::get('controlActivityContent/{activityId}/{confirm?}', ['as' => 'controlActivityContent', 'uses' => 'CommentController@controlActivityContent']);

    Route::post('submitLogs', array('as' => 'submitLogs', 'uses' => 'CommentController@submitLogs'));

    Route::post('unSubmitLogs', array('as' => 'unSubmitLogs', 'uses' => 'CommentController@unSubmitLogs'));

    Route::post('deleteLogs', array('as' => 'deleteLogs', 'uses' => 'CommentController@deleteLogs'));

    Route::post('changeUserContent', array('as' => 'changeUserContent', 'uses' => 'CommentController@changeUserContent'));

});


//ajaxController
Route::group(array('middleware' => ['auth']), function(){

    Route::post('getCityWithState', 'AjaxController@getCityWithState')->name('get.allcity.withState');

    Route::post('findPlace', 'AjaxController@findPlace')->name('find.place');
});


Route::get('gardeshEdit/{id}', 'PostController@gardeshNameEdit');

Route::post('deleteGardesh', 'PostController@deleteGardesh')->name('deleteGardesh');

Route::get('addGardeshNameTags', 'PostController@addGardeshNameTags')->name('addGardeshNameTags');

Route::get('myWordsCount', 'SeoController@myWordsCount')->name('myWordsCount');

Route::get('uiFeatures', function(){
   return view('uiFeatures');
})->name('uiFeatures');
