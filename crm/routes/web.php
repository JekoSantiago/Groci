<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'LoginController@index');
Route::post('verify', 'LoginController@verify');
Route::get('logout', 'LoginController@doLogout');
Route::get('/403', 'LoginController@userDenied');
Route::post('/auth', 'AuthController@index');

Route::get('dashboard', 'DashboardController@index');

/**
 * Order Routes
 */
Route::get('orders', 'OrdersController@index');
Route::get('orders/form', 'OrdersController@orderForm');
Route::post('orders/cart/item/{id}', 'OrdersController@cartItemJSON');
Route::post('orders/add/items', 'OrdersController@addToBasket');
Route::get('orders/view/cart/{id}', 'OrdersController@reviewBasket');
Route::post('orders/update/items', 'OrdersController@updateBasket');
Route::post('orders/save', 'OrdersController@saveOrders');
Route::get('orders/details/{id}', 'OrdersController@orderDetails');
Route::get('orders/validate/{id}', 'OrdersController@validateOrder');
Route::get('orders/punch/{id}', 'OrdersController@punchReceiptForm');
Route::post('orders/status/update', 'OrdersController@orderStatus');

Route::get('orders/form/cash-change/{id}', 'OrdersController@cashChangeForm');
Route::post('orders/save/cash-change', 'OrdersController@saveCashChange');

Route::get('orders/checker', 'OrdersController@checker');
Route::post('orders/tag', 'OrdersController@tagOrders');
Route::get('orders/update/inventory/{id}', 'OrdersController@updateInventory');

Route::post('orders/modify/cart', 'OrdersController@orderBasketModifyWithLogs');
Route::get('orders/cancel/{id}', 'OrdersController@ordersCancelForm');
Route::post('orders/save/remarks', 'OrdersController@saveCancelRemarks');
Route::get('orders/print/{id}', 'OrdersController@printView');
Route::get('orders/edit/{id}', 'OrdersController@editOrder');
Route::post('orders/create/pdf', 'OrdersController@createPDF');

/**
 * Customer Routes
 */
Route::get('customer', 'CustomerController@index');
Route::get('customer/details/{id}', 'CustomerController@details');
Route::post('customer/address/view/', 'CustomerController@addressView');
Route::get('customer/view/details/{id}/{aid}', 'CustomerController@viewCustomerDetails');
Route::get('customer/checker', 'CustomerController@checker');
Route::post('customer/confirm/status', 'CustomerController@confirmStatus');
Route::get('customer/view/address/{cid}/{aid}', 'CustomerController@viewAddress');
Route::post('customer/tag', 'CustomerController@tagCustomer');
Route::get('customer/validate/{id}/{aid}', 'CustomerController@validationForm');
Route::post('validate/code', 'CustomerController@validateCode');
Route::get('customer/all', 'CustomerController@showAllCustomer');
Route::post('customer/delete', 'CustomerController@deleteCustomer');
Route::get('customer/remarks/{cid}/{aid}/{action}', 'CustomerController@rejectRemarksForm');


/**
 * Inventory Routes
 */
Route::get('inventory', 'InventoryController@index');
Route::post('inventory/update', 'InventoryController@update');

/**
 * Maintenance Routes
 */
Route::get('cms/product/items', 'CmsController@index');
Route::get('cms/product/items/add', 'CmsController@addItem');
Route::post('cms/product/items/save', 'CmsController@saveItems');
Route::get('cms/product/items/edit/{id}', 'CmsController@editItem');
Route::post('cms/product/items/update', 'CmsController@updateItems');
Route::post('cms/product/items/status/{action}', 'CmsController@updateItemStatus');

Route::get('cms/product/items/view/price/{id}', 'CmsController@viewItemPrice');
Route::get('cms/product/items/add/price/{id}', 'CmsController@addItemPriceForm');
Route::post('cms/product/items/save/price', 'CmsController@saveItemPrice');

Route::get('cms/product/items/price', 'CmsController@updatePriceForm');
Route::post('cms/update/price', 'CmsController@save');

Route::get('cms/product/category', 'CmsController@category');
Route::get('cms/product/category/add', 'CmsController@addCategory');
Route::post('cms/product/category/save', 'CmsController@saveCategory');
Route::get('cms/product/category/edit/{id}', 'CmsController@editCategory');
Route::post('cms/product/category/update', 'CmsController@updateCategory');
Route::post('cms/product/category/status/{action}', 'CmsController@updateCategoryStatus');

Route::get('cms/charges', 'CmsController@charges');
Route::get('cms/charges/minimum/add', 'CmsController@addMinCharges');
Route::post('cms/charges/minimum/save', 'CmsController@saveMinCharges');
Route::get('cms/charges/minimum/edit/{id}', 'CmsController@editMinCharges');
Route::post('cms/charges/minimum/update', 'CmsController@modifyMinCharges');


Route::get('cms/charges/delivery/add', 'CmsController@addDeliveryCharges');
Route::post('cms/charges/delivery/save', 'CmsController@saveDeliveryCharges');
Route::get('cms/charges/delivery/edit/{id}', 'CmsController@editDeliveryCharges');
Route::post('cms/charges/delivery/update', 'CmsController@modifyDeliveryCharges');

Route::get('cms/slider', 'CmsController@sliders');
Route::get('cms/slider/add', 'CmsController@addSlider');
Route::post('cms/slider/save', 'CmsController@saveSlider');
Route::get('cms/slider/edit/{id}', 'CmsController@editSlider');
Route::post('cms/slider/update', 'CmsController@updateSlider');
Route::post('cms/slider/status/{action}', 'CmsController@updateSliderStatus');


Route::get('cms/ads', 'CmsController@ads');
Route::get('cms/ads/add', 'CmsController@addAds');
Route::post('cms/ads/save', 'CmsController@saveAds');
Route::get('cms/ads/edit/{id}', 'CmsController@editAds');
Route::post('cms/ads/update', 'CmsController@updateAds');
Route::post('cms/ads/status/{action}', 'CmsController@updateAdStatus');

Route::get('cms/store/branch', 'CmsController@storeBranch');
Route::post('cms/store/branch/refresh', 'CmsController@updateBranch');
Route::post('cms/store/branch/status/{action}', 'CmsController@updateBranchStatus');
Route::get('cms/store', 'CmsController@stores');
Route::post('cms/store/refresh', 'CmsController@updateStore');
Route::post('cms/store/status/{action}', 'CmsController@updateStoreStatus');
Route::any('cms/store/extract','CmsController@exportStoreList');

/**
 * Report Routes
 */
Route::get('report', 'ReportController@index');
Route::post('report', 'ReportController@index');
Route::post('report/stores', 'ReportController@optionStores');
Route::get('report/per-day/{param}', 'ReportController@branchPerDayReport');
Route::get('report/per-store/{param}', 'ReportController@branchPerStoreReport');
Route::get('report/view/store/{param}', 'ReportController@viewStorePerDay');
Route::get('report/view/store/orders/{param}', 'ReportController@viewStoreOrdersPerDay');
Route::get('report/view/store/orders/details/{oid}/{rn}', 'ReportController@viewOrderItems');
Route::get('report/export/{sd}/{ed}/{dc}/{code}', 'ReportController@exportReports');
Route::get('report/export/branch/daily/{id}', 'ReportController@exportBranchDailyReport');
Route::get('report/export/branch/store/{id}', 'ReportController@exportPerBranchReport');
Route::get('report/export/store/daily/{id}', 'ReportController@exportStoreDailyReport');
Route::get('report/export/products/{sd}/{ed}', 'ReportController@exportTopProducts');


Route::get('clear-all', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    echo 'success';
});

Route::get('/foo', function() {
    Artisan::call('storage:link');

    echo 'success';
});

Route::post('api/test', 'DashboardController@postData');

