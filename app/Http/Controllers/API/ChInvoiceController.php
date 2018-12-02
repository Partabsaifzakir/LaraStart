<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\ChInvoice;
use App\ChInvoiceItems;
use Gate;

class ChInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chInvoices = ChInvoice::orderBy('created_at', 'desc')->paginate(10);
        $chInvoiceItems = ChInvoiceItems::all();
        $customers = Customer::all();

        return $chInvoices;
        return $chInvoiceItems;
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
        $chInvoiceItems = collect();

        foreach($request['chInvoiceItems'] as $invoiceItem) {
        $chInvoiceItems->push(new ChInvoiceItems ([
            'ch_description' => $invoiceItem ['ch_description'],
            'ch_fares' => $invoiceItem ['ch_fares'],
            'ch_sub_total' => $invoiceItem ['ch_sub_total']
        ]));
        }

        $chInvoice = ChInvoice::create([
            'customer_id' => $request['customer_id'],
            'ch_invoice_no' => $request['ch_invoice_no'],
            'ch_invoice_date' => $request['ch_invoice_date'],
            'ch_invoice_fares_total' => $request['ch_invoice_fares_total'],
            'ch_invoice_grand_total' => $request['ch_invoice_grand_total']
        ]);

        $chInvoice->chInvoiceItems()->saveMany($chInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ChInvoice::findOrFail($id);
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
    public function searchCHI()
    {
        if($search = \Request::get('q')){
            $CHI = ChInvoice::where(function($query) use ($search){
                $query->where('ch_invoice_no','LIKE',"%$search%")
                      ->orWhereHas('customer', function($query) use ($search){
                        $query->where('customer_company_name', 'LIKE', "%{$search}%");
                      });
            })->paginate(10);
        }else{
            return ChInvoice::paginate(10);
        }
        return $CHI;
    }
}
