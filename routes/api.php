<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use App\Vendor;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*============ USER ROUTES ============*/
Route::apiResources([
    'user' => 'API\UserController'
]);

Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

/*============ VENDOR ROUTES ============*/
Route::apiResources([
    'vendor' => 'API\VendorController'
]);
Route::get('findVendor', 'API\VendorController@searchVendors');
Route::get('selectVendor', 'API\VendorController@vendor');

/*============ CUSTOMER ROUTES ============*/
Route::apiResources([
    'customer' => 'API\CustomerController'
]);
Route::get('findCustomer', 'API\CustomerController@searchCustomers');
Route::get('selectCustomer', 'API\CustomerController@customer');

/*============ VENDOR TICKET INVOICE ROUTES ============*/
Route::apiResources([
    'ticket-invoice' => 'API\TicketInvoiceController'
]);

Route::get('ticket-invoice/fetch/{ticket_invoice}', 'API\TicketInvoiceController@fetch')->name('ticket-invoice.fetch');
Route::get('findVTI', 'API\TicketInvoiceController@searchVTI');
Route::get('selectVTI', 'API\TicketInvoiceController@selectVTI');

/*============ CUSTOMER TICKET INVOICE ROUTES ============*/
Route::apiResources([
    'ct-invoice' => 'API\CtInvoiceController'
]);
