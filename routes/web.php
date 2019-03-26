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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group( [ 'as' => 'admin.', 'middleware' => [ 'auth', 'admin' ], 'prefix' => 'admin' ], function() {

	/**
	 * Admin Controller
	 */
	Route::get( '/dashboard', 'AdminController@dashboard' )->name( 'dashboard' );

	/**
	 * Product Controller
	 */
	Route::get( '/products/{product}/remove', 'ProductsController@remove' )->name( 'products.remove' );
	Route::get( '/products/trash', 'ProductsController@trash' )->name( 'products.trash' );
	Route::get( '/products/recover/{id?}', 'ProductsController@recover' )->name( 'products.recover' );
	Route::delete( '/products/destroyFromTrash/{id?}', 'ProductsController@destroyFromTrash' )->name( 'products.destroyFromTrash' );
	Route::resource( '/products', 'ProductsController' );

	/**
	 * Categories Controller
	 */
	Route::get( '/categories/{category}/remove', 'CategoriesController@remove' )->name( 'categories.remove' );
	Route::get( '/categories/trash', 'CategoriesController@trash' )->name( 'categories.trash' );
	Route::get( '/categories/recover/{id?}', 'CategoriesController@recover' )->name( 'categories.recover' );
	Route::delete( '/categories/destroyFromTrash/{id?}', 'CategoriesController@destroyFromTrash' )->name( 'categories.destroyFromTrash' );
	Route::resource( '/categories', 'CategoriesController' );

} );