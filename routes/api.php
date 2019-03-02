<?php

Route::group(['prefix' => 'slack', 'as' => 'slack.', 'namespace' => '\App\Http\Controllers\Slack'], function () {
	
	Route::post('/fetch', 'SlashCommandController@index')->name('fetch');
	
});
