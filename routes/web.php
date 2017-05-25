<?php
Route::get('/', function () {
    return view('welcome');
});

Route::get('template/{url}',function($url) {
    return view('template.'.$url);
});

Route::get('dashboard',function() {
    return view('dashboard');
});
Route::get('/dashboard/charts', 'TransactionController@charts');

Route::group(['prefix' => 'book'],function (){
    Route::get('/', 'BookController@index');
   Route::post('/', 'BookController@store');
   Route::post('/update', 'BookController@update');
   Route::get('/delete/{id}', 'BookController@delete');
});

Route::group(['prefix' => 'transaction'],function (){
   Route::get('/', 'TransactionController@transaction');
   Route::post('/store', 'TransactionController@store');
   Route::get('/item/{barcode}', 'TransactionController@item');


});
