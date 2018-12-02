<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\VhInvoice;
use App\VhInvoiceItems;
use Gate;

class VhInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vhInvoices = VhInvoice::orderBy('created_at', 'desc')->paginate(10);
        $vhInvoiceItems = VhInvoiceItems::all();
        $vendors = Vendor::all();

        return $vhInvoices;
        return $vhInvoiceItems;
        return $vendors;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vhInvoiceItems = collect();

        foreach($request['vhInvoiceItems'] as $invoiceItem) {
        $vhInvoiceItems->push(new VhInvoiceItems ([
            'vh_description' => $invoiceItem ['vh_description'],
            'vh_fares' => $invoiceItem ['vh_fares'],
            'vh_sub_total' => $invoiceItem ['vh_sub_total']
        ]));
        }

        $vhInvoice = VhInvoice::create([
            'vendor_id' => $request['vendor_id'],
            'vh_invoice_no' => $request['vh_invoice_no'],
            'vh_invoice_date' => $request['vh_invoice_date'],
            'vh_invoice_fares_total' => $request['vh_invoice_fares_total'],
            'vh_invoice_grand_total' => $request['vh_invoice_grand_total']
        ]);

        $vhInvoice->vhInvoiceItems()->saveMany($vhInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VhInvoice::findOrFail($id);
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
    public function searchVHI()
    {
        if($search = \Request::get('q')){
            $VHI = VhInvoice::where(function($query) use ($search){
                $query->where('vh_invoice_no','LIKE',"%$search%")
                      ->orWhereHas('vendor', function($query) use ($search){
                        $query->where('vendor_company_name', 'LIKE', "%{$search}%");
                      });
            })->paginate(10);
        }else{
            return VhInvoice::paginate(10);
        }
        return $VHI;
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectVHI()
    {
        $selectVHI = VhInvoice::all();

        return $selectVHI;
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch($id)
    {
        $vhInvoices = VhInvoice::findOrFail($id);

        return response()->json([
            'id' => '',
            'customer_id' => '',
            'ch_invoice_no' => $vhInvoices->vh_invoice_no,
            'ch_invoice_date' => $vhInvoices->vh_invoice_date,
            'ch_invoice_fares_total' => $vhInvoices->vh_invoice_fares_total,
            'ch_invoice_grand_total' => $vhInvoices->vh_invoice_grand_total,
            'chInvoiceItems' => $vhInvoices->vhInvoiceItems->map(function ($item) {
                return [
                    // get the relevant $item->property for each key below
                    'id' => "",
                    'ch_invoice_id' => "",
                    'ch_description' => $item->vh_description,
                    'ch_fares' => $item->vh_fares,
                    'ch_sub_total' => $item->vh_sub_total
                ];
            })->all()
        ]);
    }
}
