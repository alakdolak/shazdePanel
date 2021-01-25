<?php

use Illuminate\Support\Facades\Route;


//news.list

Route::prefix('news')->group(function(){
    Route::get('/list', 'NewsController@newsList')->name('news.list');
    Route::get('/new', 'NewsController@newsNewPage')->name('news.new');
    Route::get('/edit/{id}', 'NewsController@editNewsPage')->name('news.edit');

    Route::get('/advertise/page', 'NewsAdvertisementController@advertisePage')->name('news.advertisement');

    Route::get('/tagSearch', 'NewsController@newsTagSearch')->name('news.tagSearch');

    Route::post('/uploadPic','NewsController@uploadNewsPic')->name('news.uploadDescPic');
    Route::post('/store','NewsController@storeNews')->name('news.store');

    Route::delete('/delete', 'NewsController@deleteNews')->name('news.delete');
});
