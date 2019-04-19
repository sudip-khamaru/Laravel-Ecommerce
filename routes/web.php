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
	Route::view( '/products/extras', 'admin.partials.extras' )->name( 'products.extras' );
	Route::resource( '/products', 'ProductsController' );

	/**
	 * Categories Controller
	 */
	Route::get( '/categories/{category}/remove', 'CategoriesController@remove' )->name( 'categories.remove' );
	Route::get( '/categories/trash', 'CategoriesController@trash' )->name( 'categories.trash' );
	Route::get( '/categories/recover/{id?}', 'CategoriesController@recover' )->name( 'categories.recover' );
	Route::delete( '/categories/destroyFromTrash/{id?}', 'CategoriesController@destroyFromTrash' )->name( 'categories.destroyFromTrash' );
	Route::resource( '/categories', 'CategoriesController' );

	/**
	 * Profiles Controller
	 */
	Route::get( '/profiles/states/{id?}', 'ProfilesController@getStates' )->name( 'profiles.states' );
	Route::get( '/profiles/cities/{id?}', 'ProfilesController@getCities' )->name( 'profiles.cities' );
	Route::get( '/profiles/{profile}/remove', 'ProfilesController@remove' )->name( 'profiles.remove' );
	Route::get( '/profiles/trash', 'ProfilesController@trash' )->name( 'profiles.trash' );
	Route::get( '/profiles/recover/{id?}', 'ProfilesController@recover' )->name( 'profiles.recover' );
	Route::delete( '/profiles/destroyFromTrash/{id?}', 'ProfilesController@destroyFromTrash' )->name( 'profiles.destroyFromTrash' );
	Route::resource( '/profiles', 'ProfilesController' );

} );

Route::group( [ 'as' => 'products.', 'prefix' => 'products' ], function() {

	Route::get( '/', 'ProductsController@showAllProduct' )->name( 'showAllProduct' );
	Route::get( '/{product}', 'ProductsController@showSingleProduct' )->name( 'showSingleProduct' );
	Route::get( '/add-to-cart/{product}', 'ProductsController@addToCart' )->name( 'addToCart' );

} );

Route::group( [ 'as' => 'cart.', 'prefix' => 'cart' ], function() {

	Route::get( '/', 'ProductsController@viewCart' )->name( 'viewCart' );
	Route::post( '/remove/{product}', 'ProductsController@removeSingleProductFromCart' )->name( 'removeSingleProductFromCart' );
	Route::post( '/update/{product}', 'ProductsController@updateSingleProductInCart' )->name( 'updateSingleProductInCart' );

} );

Route::group( [ 'as' => 'checkout.', 'prefix' => 'checkout' ], function() {

	Route::resource( '/', 'OrdersController' );

} );

Route::group( [ 'as' => 'payment.', 'prefix' => 'payment' ], function() {

	Route::post( '/paypalPayment', 'OrdersController@paypalPaymentGateway' )->name( 'paypalPayment' );
	Route::get( '/processPaypalPayment', 'OrdersController@processPaypalPayment' )->name( 'processPaypalPayment' );
	Route::get( '/cancelPaypalPayment', 'OrdersController@cancelPaypalPayment' )->name( 'cancelPaypalPayment' );

} );

