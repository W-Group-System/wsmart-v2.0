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
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('user_management', 'UserController@index');
Route::get('user_module_access/{id}','UserController@user_module_access');
Route::post('get_users', 'UserController@getUsers');
Route::post('store_users','UserController@store');
Route::post('update_users','UserController@update');
Route::post('deactivate_user','UserController@deactivate');
Route::post('activate_user','UserController@activate');
Route::post('store_user_module_access', 'UserController@storeUserModuleAccess');
