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

/*============ VENDOR HOTEL INVOICE ROUTES ============*/
Route::apiResources([
    'vh-invoice' => 'API\VhInvoiceController'
]);

Route::get('findVHI', 'API\VhInvoiceController@searchVHI');
Route::get('selectVHI', 'API\VhInvoiceController@selectVHI');
Route::get('vh-invoice/fetch/{vh_invoice}', 'API\VhInvoiceController@fetch')->name('vh-invoice.fetch');

/*============ CUSTOMER HOTEL INVOICE ROUTES ============*/
Route::apiResources([
    'ch-invoice' => 'API\ChInvoiceController'
]);
Route::get('findCHI', 'API\ChInvoiceController@searchCHI');

/*============ VENDOR VISA INVOICE ROUTES ============*/
Route::apiResources([
    'vv-invoice' => 'API\VvInvoiceController'
]);
Route::get('selectVVI', 'API\VvInvoiceController@selectVVI');
Route::get('findVVI', 'API\VvInvoiceController@searchVVI');
Route::get('vv-invoice/fetch/{vv_invoice}', 'API\VvInvoiceController@fetch')->name('vv-invoice.fetch');

/*============ CUSTOMER VISA INVOICE ROUTES ============*/
Route::apiResources([
    'cv-invoice' => 'API\CvInvoiceController'
]);

Route::get('findCVI', 'API\CvInvoiceController@searchCVI');