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

// User
Route::get('user_management', 'UserController@index');
Route::get('user_module_access/{id}','UserController@user_module_access');
Route::post('get_users', 'UserController@getUsers');
Route::post('store_users','UserController@store');
Route::post('update_users','UserController@update');
Route::post('deactivate_user','UserController@deactivate');
Route::post('activate_user','UserController@activate');
Route::post('store_user_module_access', 'UserController@storeUserModuleAccess');

// Department
Route::get('department', 'DepartmentController@index');
Route::post('get_department','DepartmentController@getDepartment');

// Subsidiary
Route::get('subsidiary','SubsidiaryController@index');
Route::post('get-subsidiary','SubsidiaryController@getSubsidiary');
Route::post('store-subsidiary','SubsidiaryController@store');
Route::post('update-subsidiary','SubsidiaryController@update');
Route::post('deactivate-subsidiary','SubsidiaryController@deactivate');
Route::post('activate-subsidiary','SubsidiaryController@activate');

// Uom
Route::get('uoms','UomController@index');
Route::post('get-uom','UomController@getUom');
Route::post('store-uom','UomController@store'); 
Route::post('update-uom','UomController@update');
Route::post('deactivate-uom','UomController@deactivate');
Route::post('activate-uom','UomController@activate');

// Purchase Request
Route::get('purchase-request','PurchaseRequestController@index');
Route::get('create-purchase-request','PurchaseRequestController@create');
Route::post('get-purchase-request','PurchaseRequestController@getPurchaseRequest');
Route::post('refreshInvetory', 'RefreshController@refreshInventory');
