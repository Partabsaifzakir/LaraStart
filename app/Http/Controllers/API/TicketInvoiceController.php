<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use App\TicketInvoice;
use App\TicketInvoiceItems;
use Gate;


class TicketInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketInvoices = TicketInvoice::orderBy('created_at', 'desc')->paginate(20);
        $ticketInvoiceItems = TicketInvoiceItems::all();
        $vendors = Vendor::all();

        return $ticketInvoices;
        return $ticketInvoiceItems;
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
        $ticketInvoiceItems = collect();

        foreach($request['ticketInvoiceItems'] as $invoiceItem) {
        $ticketInvoiceItems->push(new TicketInvoiceItems ([
            'passenger_name' => $invoiceItem ['passenger_name'],
            'ticket_no' => $invoiceItem ['ticket_no'],
            'flight_no' => $invoiceItem ['flight_no'],
            'departure_date' => $invoiceItem ['departure_date'],
            'sector' => $invoiceItem ['sector'],
            'tax_SB' => $invoiceItem ['tax_SB'],
            'tax_SRP' => $invoiceItem ['tax_SRP'],
            'tax_YQ' => $invoiceItem ['tax_YQ'],
            'tax_RG' => $invoiceItem ['tax_RG'],
            'tax_PK' => $invoiceItem ['tax_PK'],
            'tax_YR' => $invoiceItem ['tax_YR'],
            'tax_SF' => $invoiceItem ['tax_SF'],
            'tax_PTT' => $invoiceItem ['tax_PTT'],
            'tax_OAS' => $invoiceItem ['tax_OAS'],
            'tax_PSF' => $invoiceItem ['tax_PSF'],
            'tax_PB' => $invoiceItem ['tax_PB'],
            'tax_OAD' => $invoiceItem ['tax_OAD'],
            'fares' => $invoiceItem ['fares'],
            'total_tax_breakup' => $invoiceItem ['total_tax_breakup'],
            'sub_total' => $invoiceItem ['sub_total']
        ]));
        }

        $ticketInvoice = TicketInvoice::create([
            'vendor_id' => $request['vendor_id'],
            'ticket_invoice_no' => $request['ticket_invoice_no'],
            'ticket_invoice_date' => $request['ticket_invoice_date'],
            'ticket_invoice_fares_total' => $request['ticket_invoice_fares_total'],
            'ticket_invoice_taxes_grand_total' => $request['ticket_invoice_taxes_grand_total'],
            'ticket_invoice_grand_total' => $request['ticket_invoice_grand_total'],
            'ticket_invoice_grand_total_words' => $request ['ticket_invoice_grand_total_words'],
            'ticket_invoice_terms' => $request ['ticket_invoice_terms']
        ]);

        $ticketInvoice->ticketInvoiceItems()->saveMany($ticketInvoiceItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch($id)
    {
        $ticketInvoices = TicketInvoice::findOrFail($id);

        return response()->json([
            'id' => '',
            'customer_id' => '',
            'ct_invoice_no' => $ticketInvoices->ticket_invoice_no,
            'ct_invoice_date' => $ticketInvoices->ticket_invoice_date,
            'ct_invoice_fares_total' => $ticketInvoices->ticket_invoice_fares_total,
            'ct_invoice_taxes_grand_total' => $ticketInvoices->ticket_invoice_taxes_grand_total,
            'ct_invoice_grand_total' => $ticketInvoices->ticket_invoice_grand_total,
            'ct_invoice_grand_total_words' => $ticketInvoices->ticket_invoice_grand_total_words,
            'ct_invoice_terms' => $ticketInvoices->ticket_invoice_terms,
            'ctInvoiceItems' => $ticketInvoices->ticketInvoiceItems->map(function ($item) {
                return [
                    // get the relevant $item->property for each key below
                    'id' => "",
                    'ct_invoice_id' => "",
                    'ct_passenger_name' => $item->passenger_name,
                    'ct_ticket_no' => $item->ticket_no,
                    'ct_flight_no' => $item->flight_no,
                    'ct_departure_date' => $item->departure_date,
                    'ct_sector' => $item->sector,
                    'ct_fares' => $item->fares,
                    'ct_tax_SB' => $item->tax_SB,
                    'ct_tax_SRP' => $item->tax_SRP,
                    'ct_tax_YQ' => $item->tax_YQ,
                    'ct_tax_RG' => $item->tax_RG,
                    'ct_tax_PK' => $item->tax_PK,
                    'ct_tax_YR' => $item->tax_YR,
                    'ct_tax_SF' => $item->tax_SF,
                    'ct_tax_PTT' => $item->tax_PTT,
                    'ct_tax_OAS' => $item->tax_OAS,
                    'ct_tax_PSF' => $item->tax_PSF,
                    'ct_tax_PB' => $item->tax_PB,
                    'ct_tax_OAD' => $item->tax_OAD,
                    'ct_total_tax_breakup' => $item->total_tax_breakup,
                    'ct_sub_total' => $item->sub_total
                ];
            })->all()
        ]);
    }
}
