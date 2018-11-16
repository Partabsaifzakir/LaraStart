<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\CtInvoice;
use App\CtInvoiceItems;
use Gate;

class CtInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctInvoices = CtInvoice::orderBy('created_at', 'desc')->paginate(10);
        $ctInvoiceItems = CtInvoiceItems::all();
        $customers = Customer::all();

        return $ctInvoices;
        return $ctInvoiceItems;
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
        $ctInvoiceItems = collect();

        foreach($request['ctInvoiceItems'] as $ctInvoiceItem) {
        $ctInvoiceItems->push(new CtInvoiceItems ([
            'ct_passenger_name' => $ctInvoiceItem ['ct_passenger_name'],
            'ct_ticket_no' => $ctInvoiceItem ['ct_ticket_no'],
            'ct_flight_no' => $ctInvoiceItem ['ct_flight_no'],
            'ct_departure_date' => $ctInvoiceItem ['ct_departure_date'],
            'ct_sector' => $ctInvoiceItem ['ct_sector'],
            'ct_tax_SB' => $ctInvoiceItem ['ct_tax_SB'],
            'ct_tax_SRP' => $ctInvoiceItem ['ct_tax_SRP'],
            'ct_tax_YQ' => $ctInvoiceItem ['ct_tax_YQ'],
            'ct_tax_RG' => $ctInvoiceItem ['ct_tax_RG'],
            'ct_tax_PK' => $ctInvoiceItem ['ct_tax_PK'],
            'ct_tax_YR' => $ctInvoiceItem ['ct_tax_YR'],
            'ct_tax_SF' => $ctInvoiceItem ['ct_tax_SF'],
            'ct_tax_PTT' => $ctInvoiceItem ['ct_tax_PTT'],
            'ct_tax_OAS' => $ctInvoiceItem ['ct_tax_OAS'],
            'ct_tax_PSF' => $ctInvoiceItem ['ct_tax_PSF'],
            'ct_tax_PB' => $ctInvoiceItem ['ct_tax_PB'],
            'ct_tax_OAD' => $ctInvoiceItem ['ct_tax_OAD'],
            'ct_fares' => $ctInvoiceItem ['ct_fares'],
            'ct_total_tax_breakup' => $ctInvoiceItem ['ct_total_tax_breakup'],
            'ct_sub_total' => $ctInvoiceItem ['ct_sub_total']
        ]));
        }

        $ctInvoice = CtInvoice::create([
            'customer_id' => $request['customer_id'],
            'ct_invoice_no' => $request['ct_invoice_no'],
            'ct_invoice_date' => $request['ct_invoice_date'],
            'ct_invoice_fares_total' => $request['ct_invoice_fares_total'],

            'ct_invoice_total_tax_SB' => $request['ct_invoice_total_tax_SB'],
            'ct_invoice_total_tax_SRP' => $request['ct_invoice_total_tax_SRP'],
            'ct_invoice_total_tax_YQ' => $request['ct_invoice_total_tax_YQ'],
            'ct_invoice_total_tax_RG' => $request['ct_invoice_total_tax_RG'],
            'ct_invoice_total_tax_PK' => $request['ct_invoice_total_tax_PK'],
            'ct_invoice_total_tax_YR' => $request['ct_invoice_total_tax_YR'],
            'ct_invoice_total_tax_SF' => $request['ct_invoice_total_tax_SF'],
            'ct_invoice_total_tax_PTT' => $request['ct_invoice_total_tax_PTT'],
            'ct_invoice_total_tax_OAS' => $request['ct_invoice_total_tax_OAS'],
            'ct_invoice_total_tax_PSF' => $request['ct_invoice_total_tax_PSF'],
            'ct_invoice_total_tax_PB' => $request['ct_invoice_total_tax_PB'],
            'ct_invoice_total_tax_OAD' => $request['ct_invoice_total_tax_OAD'],


            'ct_invoice_taxes_grand_total' => $request['ct_invoice_taxes_grand_total'],
            'ct_invoice_grand_total' => $request['ct_invoice_grand_total'],
            'ct_invoice_grand_total_words' => $request ['ct_invoice_grand_total_words'],
            'ct_invoice_terms' => $request ['ct_invoice_terms']
        ]);

        $ctInvoice->ctInvoiceItems()->saveMany($ctInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CtInvoice::findOrFail($id);
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

}
