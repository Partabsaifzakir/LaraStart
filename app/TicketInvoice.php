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
