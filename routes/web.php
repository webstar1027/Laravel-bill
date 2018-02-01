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

// Route::auth();

Route::get('/login', 'UserController@login');
Route::post('/login', 'UserController@doLogin');
Route::get('/logout', 'UserController@logout');

Route::get('/register', 'UserController@register');
Route::post('/register', 'UserController@sendtoken');
Route::post('/doregister', 'UserController@doRegister');

Route::get('reset-password/{token}', 'UserController@getResetPassword');
Route::post('reset-password/{token}', 'UserController@resetPassword');
Route::get('success-password', 'UserController@getForgotPasswordSuccess');

Route::group(array('middleware' => 'auth'), function(){
    Route::get('/', 'HomeController@index');
    Route::get('/users', 'UserController@index');
    Route::get('/account', 'StoreController@index');
    Route::post('/updateProfile', 'StoreController@updateProfile');
    Route::get('/orders', 'OrdersController@index');
    Route::get('/category', 'CategoryController@index');
    Route::get('/menu', 'MenuController@index');

    Route::get('/rewards', 'RewardsController@index');
    Route::get('/promotion', 'PromotionController@index');

    Route::get('/records', 'RecordsController@index');
    Route::get('/records/{month}', 'RecordsController@index');
    Route::get('/records/{month}/{phone}', 'RecordsController@getRecords');
    Route::post('/records/pay', 'RecordsController@pay');
    Route::get('/disputes', 'DisputesController@index');
    Route::get('/disputes/{dispute_id}', 'DisputesController@responsd');
    Route::post('/filldispute', 'DisputesController@fileDispute');
    Route::post('/sendtoken', 'DisputesController@sendToken');
    Route::post('/getrecorddetail', 'RecordsController@getRecordDetail');
    Route::get('/phoneline', 'PhonelineController@index');
});

Route::group(['prefix' => 'api'], function() {
    Route::post('/register', 'APIController@register');
    Route::post('/login', 'APIController@login');
    Route::post('/socialLogin', 'APIController@socialLogin');
    Route::post('/forgotPassword', 'APIController@forgotPassword');
    Route::post('/updateUserInfo', 'APIController@updateUserInfo');
    Route::post('/getUserProfile', 'APIController@getUserProfile');
    Route::post('/updateProfilePhoto', 'APIController@updateProfilePhoto');
    Route::post('/updateUserPassword', 'APIController@updateUserPassword');
    Route::get('/getCategories', 'APIController@getCategories');
    Route::post('/getMenus', 'APIController@getMenus');
    Route::post('/getMenu', 'APIController@getMenu');
    Route::post('/placeOrder', 'APIController@placeOrder');
    Route::post('/refundOrders', 'APIController@refundOrders');
    Route::post('/getOrders', 'APIController@getOrders');
    Route::post('/getOrderDetails', 'APIController@getOrderDetails');
});


Route::group(['prefix' => 'store/api'], function() {
    Route::post('/storeLogin', 'APIController@storeLogin');
    Route::post('/authorizeCode', 'APIController@authorizeCode');
    Route::post('/getStoreById', 'APIController@getStoreById');
    Route::post('/updateStore', 'APIController@updateStoreInfo');
    Route::get('/getStores', 'APIController@getStores');
    Route::post('/getNewOrdersByStore', 'APIController@getNewOrdersByStore');
    Route::post('/getCompletedOrdersByStore', 'APIController@getCompletedOrdersByStore');
    Route::post('/searchOrders', 'APIController@searchOrders');
    Route::post('/getCustomerInfo', 'APIController@getCustomerInfo');
    Route::post('/refundOrder', 'APIController@refundOrderFromStore');
    Route::post('/addRewardsToUser', 'APIController@addRewardsToUser');

});

