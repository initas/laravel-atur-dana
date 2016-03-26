<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => '/api/v1'], function () {

	Route::group(['prefix' => '/collections'], function () {
		Route::get('/dashboard', 'API\CollectionController@getDashboard');
	});

	Route::group(['prefix' => '/sources'], function () {

		Route::get('/', 'API\SourceController@getAll');
		Route::post('/', 'API\SourceController@postNew');

		Route::group(['prefix' => '/{id}'], function () {

			Route::get('/', 'API\SourceController@getOne');
			Route::put('/', 'API\SourceController@putUpdate');
			Route::delete('/', 'API\SourceController@deleteRecord');

		});

	});

	Route::group(['prefix' => '/transaction-categories'], function () {

		Route::get('/', 'API\TransactionCategoryController@getAll');
		Route::post('/', 'API\TransactionCategoryController@postNew');

		Route::group(['prefix' => '/{id}'], function () {

			Route::get('/', 'API\TransactionCategoryController@getOne');
			Route::put('/', 'API\TransactionCategoryController@putUpdate');
			Route::delete('/', 'API\TransactionCategoryController@deleteRecord');

		});

	});

	Route::group(['prefix' => '/transaction-comments'], function () {

		Route::get('/', 'API\TransactionCommentController@getAll');
		Route::post('/', 'API\TransactionCommentController@postNew');

		Route::group(['prefix' => '/{id}'], function () {

			Route::get('/', 'API\TransactionCommentController@getOne');
			Route::put('/', 'API\TransactionCommentController@putUpdate');
			Route::delete('/', 'API\TransactionCommentController@deleteRecord');

		});

	});

	Route::group(['prefix' => '/transactions'], function () {

		Route::get('/', 'API\TransactionController@getAll');
		Route::post('/', 'API\TransactionController@postNew');

		Route::group(['prefix' => '/{id}'], function () {

			Route::get('/', 'API\TransactionController@getOne');
			Route::put('/', 'API\TransactionController@putUpdate');
			Route::delete('/', 'API\TransactionController@deleteRecord');

		});

	});

	Route::group(['prefix' => '/users'], function () {

		Route::get('/', 'API\UserController@getAll');
		Route::post('/', 'API\UserController@postNew');
		Route::post('/login', 'API\UserController@postLogin');

		Route::group(['prefix' => '/{id}'], function () {

			Route::get('/', 'API\UserController@getOne');
			Route::put('/', 'API\UserController@putUpdate');

		});

	});

});
