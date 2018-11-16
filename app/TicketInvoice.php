<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\TicketInvoiceItems;

class TicketInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'vendor_id',
        'ticket_invoice_no',
        'ticket_invoice_date',
        'ticket_invoice_fares_total',

        
        'ticket_invoice_total_tax_SB',
        'ticket_invoice_total_tax_SRP',
        'ticket_invoice_total_tax_YQ',
        'ticket_invoice_total_tax_RG',
        'ticket_invoice_total_tax_PK',
        'ticket_invoice_total_tax_YR',
        'ticket_invoice_total_tax_SF',
        'ticket_invoice_total_tax_PTT',
        'ticket_invoice_total_tax_OAS',
        'ticket_invoice_total_tax_PSF',
        'ticket_invoice_total_tax_PB',
        'ticket_invoice_total_tax_OAD',


        'ticket_invoice_taxes_grand_total',
        'ticket_invoice_grand_total',
        'ticket_invoice_grand_total_words',
        'ticket_invoice_terms'

    ];

    protected $with = [
        'vendor',
        'ticketInvoiceItems',
    ];

    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }

    public function ticketInvoiceItems(){
        return $this->hasMany('App\TicketInvoiceItems');
    }
}
