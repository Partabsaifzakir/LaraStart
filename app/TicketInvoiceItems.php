<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\TicketInvoice;

class TicketInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'ticket_invoice_id',
        'passenger_name',
        'ticket_no',
        'flight_no',
        'departure_date',
        'sector',
        'fares',
        'tax_SB',
        'tax_SRP',
        'tax_YQ',
        'tax_RG',
        'tax_PK',
        'tax_YR',
        'tax_SF',
        'tax_PTT',
        'tax_OAS',
        'tax_PSF',
        'tax_PB',
        'tax_OAD',
        'total_tax_breakup',
        'sub_total'
    ];

    public function ticketInvoice(){
        return $this->belongsTo('App\TicketInvoice');
    }
}
