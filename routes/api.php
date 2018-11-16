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

/*============ CUSTOMER ROUTES ============*/
Route::apiResources([
    'customer' => 'API\CustomerController'
    ]);
    Route::get('findCustomer', 'API\CustomerController@searchCustomers');
    
    /*============ VENDOR TICKET INVOICE ROUTES ============*/
Route::apiResources([
    'ticket-invoice' => 'API\TicketInvoiceController'
]);
Route::get('ticket-invoice/fetch/{ticket_invoice}', 'API\TicketInvoiceController@fetch')->name('ticket-invoice.fetch');
Route::get('findVTI', 'API\TicketInvoiceController@searchVTI');

/*============ CUSTOMER TICKET INVOICE ROUTES ============*/
Route::apiResources([
    'ct-invoice' => 'API\CtInvoiceController'
]);

Route::get('/search', function(){
    $queryString = Input::get('queryString');

    $vendors = Vendor::where('vendor_company_name', 'LIKE', '%'.$queryString.'%')->get();

    return response()->json($vendors);
});