<?php

use Illuminate\Support\Facades\Route;


Route::prefix('v1')->namespace('API')->group(function () {

	Route::prefix('cards')->group(
		function () {
			Route::get('/', 'CardController@index');
			Route::post('/', 'CardController@store');
			Route::put('{slug}', 'CardController@update');
			Route::put('{slug}/request_update', 'CardController@request_update');
			Route::delete('{slug}', 'CardController@remove');
			Route::get('{slug}', 'CardController@show');
		}
	);
});
