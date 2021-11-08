<?php

use App\Models\Account;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('/', 'HomeController@index');
Route::post('/validate', 'LocatorController@index');
Route::get('/nearest/stores/{lat}/{lon}', 'LocatorController@nearestStore');
Route::post('/search/stores', 'LocatorController@searchNearestStore');


/**
 * Account Routes
 * Routes defined for account management
 */

Route::get('login', 'AccountController@index');
Route::get('login/{code}', 'AccountController@index');
Route::get('register', 'AccountController@register');
Route::get('register/{code}', 'AccountController@register');
Route::get('redirect/{action}', 'AccountController@redirectPage');
Route::get('redirect/{action}/{code}', 'AccountController@redirectPage');
Route::get('callback/{provider}', 'AccountController@callback');
Route::post('re-order', 'AccountController@reOrder');
Route::get('activate/{email}', 'AccountController@activateAccount');
Route::get('send/mail', 'AccountController@forgotPassword');
Route::get('change-password/{param}', 'AccountController@changePassword');
Route::get('logout', 'AccountController@doLogout');

Route::post('account/login', 'AccountController@userLogin');
Route::post('account/register', 'AccountController@userRegister');
Route::get('account/profile', 'AccountController@viewProfile');
Route::post('account/profile/update', 'AccountController@updateProfile');
Route::get('account/address', 'AccountController@viewAddress');
Route::get('account/orders', 'AccountController@viewOrders');
Route::post('account/delivery/address', 'AccountController@customerDeliveryAddress');
Route::post('account/address/save', 'AccountController@accountAddressSave');
Route::get('account/redirect/{provider}/{action}', 'AccountController@redirect');
Route::get('account/redirect/{provider}/{action}/{code}', 'AccountController@redirect');
Route::get('account/order/details/{id}', 'AccountController@orderDetails');
Route::post('account/details', 'AccountController@customerDetails');
Route::post('account/stores', 'AccountController@showStorePerProvince');
Route::post('/stores/per-city', 'AccountController@showStorePerCity');
Route::get('/address-list','AccountController@addressList');
Route::post('/address-del','AccountController@deleteAddress');
Route::post('/address-update','AccountController@updateAddress');

Route::post('account/view/address', 'AccountController@showCustomerAddress');
Route::post('account/transaction', 'AccountController@selectTransaction');
Route::post('account/send/notification', 'AccountController@forgotPasswordNotification');
Route::post('account/change-password', 'AccountController@updateAccountPassword');
Route::post('account/check/code', 'AccountController@checkCode');
Route::post('account/change/store', 'AccountController@changeStore');

/**
 * Product Routes
 */

Route::get('category/{id}', 'ShopController@index');
Route::post('add-to-cart', 'ShopController@addToCart');
Route::post('update-basket', 'ShopController@updateBasket');
Route::post('search', 'ShopController@search');
Route::get('search/result/{key}', 'ShopController@searchResult');
Route::get('sale', 'ShopController@saleItems');

/**
 * Checkout Routes
 */

Route::get('checkout', 'CheckoutController@index');
Route::get('cancel/order', 'CheckoutController@cancelOrders');
Route::post('cance/order', 'CheckoutController@cancelPendingOrder');
Route::post('save/order', 'CheckoutController@saveOrders');
Route::get('success/{id}', 'CheckoutController@success');

/**
 * About Routes
 * Routes defined for about us page
 */
Route::get('about-us', 'AboutController@index')->name('about');
Route::get('contact-us','AboutController@contact')->name('contact');
Route::get('terms-condition','AboutController@tnc')->name('tnc');
Route::get('FAQ','AboutController@faq')->name('faq');
Route::get('data-privacy','AboutController@privacy')->name('privacy');



Route::get('clear-all', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    echo 'success';
});


Route::get('/test','CheckoutController@testMail');

