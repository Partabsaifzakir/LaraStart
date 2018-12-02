<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\CvInvoice;
use App\CvInvoiceItems;
use Gate;

class CvInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cvInvoices = CvInvoice::orderBy('created_at', 'desc')->paginate(10);
        $cvInvoiceItems = CvInvoiceItems::all();
        $customers = Customer::all();

        return $cvInvoices;
        return $cvInvoiceItems;
        return $customers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cvInvoiceItems = collect();

        foreach($request['cvInvoiceItems'] as $invoiceItem) {
        $cvInvoiceItems->push(new CvInvoiceItems ([
            'cv_passenger_name' => $invoiceItem ['cv_passenger_name'], 
            'cv_description' => $invoiceItem ['cv_description'], 
            'cv_fares' => $invoiceItem ['cv_fares'],
            'cv_sub_total' => $invoiceItem ['cv_sub_total']
        ]));
        }

        $cvInvoice = CvInvoice::create([
            'customer_id' => $request['customer_id'],
            'cv_invoice_no' => $request['cv_invoice_no'],
            'cv_invoice_date' => $request['cv_invoice_date'],
            'cv_invoice_fares_total' => $request['cv_invoice_fares_total'],
            'cv_invoice_grand_total' => $request['cv_invoice_grand_total']
        ]);

        $cvInvoice->cvInvoiceItems()->saveMany($cvInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CvInvoice::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
                /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchCVI()
    {
        if($search = \Request::get('q')){
            $CVI = CvInvoice::where(function($query) use ($search){
                $query->where('cv_invoice_no','LIKE',"%$search%")
                      ->orWhereHas('customer', function($query) use ($search){
                        $query->where('customer_name', 'LIKE', "%{$search}%");
                      });
            })->paginate(10);
        }else{
            return CvInvoice::paginate(10);
        }
        return $CVI;
    }
}
