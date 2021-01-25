<?php

use Illuminate\Support\Facades\Route;


//news.list

Route::prefix('news')->group(function(){
    Route::get('/list', 'NewsController@newsList')->name('news.list');
    Route::get('/new', 'NewsController@newsNewPage')->name('news.new');
    Route::get('/edit/{id}', 'NewsController@editNewsPage')->name('news.edit');

    Route::get('/tagSearch', 'NewsController@newsTagSearch')->name('news.tagSearch');

    Route::post('/uploadPic','NewsController@uploadNewsPic')->name('news.uploadDescPic');
    Route::post('/store','NewsController@storeNews')->name('news.store');


    Route::post('/addToTopNews', 'NewsController@addToTopNews')->name('news.addToTopNews');
    Route::post('/removeAllTops', 'NewsController@removeAllTopNews')->name('news.removeAllTopNews');
    Route::delete('/delete', 'NewsController@deleteNews')->name('news.delete');
});
