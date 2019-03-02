<?php

Auth::routes();

Route::group([], function () {
	
	Route::get('/', 'WelcomeController@index')->name('welcome');

});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin'], function () {
	
	Route::get('/home', 'HomeController@index')->name('home');
	
});
