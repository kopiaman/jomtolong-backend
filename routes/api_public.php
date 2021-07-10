<?php

use Illuminate\Support\Facades\Route;


// Route::group(
// 	['prefix' => 'v1', 'namespace' => 'API',],
// 	function () {

// 		/*
//          * companies
//          */
// 		Route::group(
// 			['prefix' => 'cards'],
// 			function () {
// 				Route::get('/', 'CardController@index');
// 				Route::post('store', 'CardController@store');
// 				Route::put('{slug}', 'CardController@update');
// 				Route::delete('{slug}', 'CardController@remove');
// 				Route::get('{slug}', 'CardController@show');
// 			}
// 		);
// 	}
// );


// Route::prefix('admin')->group(function () {

// 	Route::get('/users', function () {
// 		// Matches The "/admin/users" URL
// 	});
// });



Route::prefix('v1')->namespace('API')->group(function () {

	Route::prefix('cards')->group(
		function () {
			Route::get('/', 'CardController@index');
			Route::post('store', 'CardController@store');
			Route::put('{slug}', 'CardController@update');
			Route::delete('{slug}', 'CardController@remove');
			Route::get('{slug}', 'CardController@show');
		}
	);
});
