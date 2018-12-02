<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\VvInvoice;
use App\VvInvoiceItems;
use Gate;

class VvInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vvInvoices = VvInvoice::orderBy('created_at', 'desc')->paginate(10);
        $vvInvoiceItems = VvInvoiceItems::all();
        $vendors = Vendor::all();

        return $vvInvoices;
        return $vvInvoiceItems;
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
        $vvInvoiceItems = collect();

        foreach($request['vvInvoiceItems'] as $invoiceItem) {
        $vvInvoiceItems->push(new VvInvoiceItems ([
            'vv_passenger_name' => $invoiceItem ['vv_passenger_name'],
            'vv_description' => $invoiceItem ['vv_description'],
            'vv_fares' => $invoiceItem ['vv_fares'],
            'vv_sub_total' => $invoiceItem ['vv_sub_total']
        ]));
        }

        $vvInvoice = VvInvoice::create([
            'vendor_id' => $request['vendor_id'],
            'vv_invoice_no' => $request['vv_invoice_no'],
            'vv_invoice_date' => $request['vv_invoice_date'],
            'vv_invoice_fares_total' => $request['vv_invoice_fares_total'],
            'vv_invoice_grand_total' => $request['vv_invoice_grand_total']
        ]);

        $vvInvoice->vvInvoiceItems()->saveMany($vvInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return VvInvoice::findOrFail($id);
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
    public function searchVVI()
    {
        if($search = \Request::get('q')){
            $VVI = VvInvoice::where(function($query) use ($search){
                $query->where('vv_invoice_no','LIKE',"%$search%")
                      ->orWhereHas('vendor', function($query) use ($search){
                        $query->where('vendor_company_name', 'LIKE', "%{$search}%");
                      });
            })->paginate(10);
        }else{
            return VvInvoice::paginate(10);
        }
        return $VVI;
    }
            /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectVVI()
    {
        $selectVVI = VvInvoice::all();

        return $selectVVI;
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch($id)
    {
        $vvInvoices = VvInvoice::findOrFail($id);

        return response()->json([
            'id' => '',
            'customer_id' => '',
            'cv_invoice_no' => $vvInvoices->vv_invoice_no,
            'cv_invoice_date' => $vvInvoices->vv_invoice_date,
            'cv_invoice_fares_total' => $vvInvoices->vv_invoice_fares_total,
            'cv_invoice_grand_total' => $vvInvoices->vv_invoice_grand_total,
            'cvInvoiceItems' => $vvInvoices->vvInvoiceItems->map(function ($item) {
                return [
                    // get the relevant $item->property for each key below
                    'id' => "",
                    'cv_invoice_id' => "",
                    'cv_passenger_name' => $item->vv_passenger_name,
                    'cv_description' => $item->vv_description,
                    'cv_fares' => $item->vv_fares,
                    'cv_sub_total' => $item->vv_sub_total
                ];
            })->all()
        ]);
    }
}
