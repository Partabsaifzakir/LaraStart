<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\CtInvoice;

class CtInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'ct_invoice_id',
        'ct_passenger_name',
        'ct_ticket_no',
        'ct_flight_no',
        'ct_departure_date',
        'ct_sector',
        'ct_fares',
        'ct_tax_SB',
        'ct_tax_SRP',
        'ct_tax_YQ',
        'ct_tax_RG',
        'ct_tax_PK',
        'ct_tax_YR',
        'ct_tax_SF',
        'ct_tax_PTT',
        'ct_tax_OAS',
        'ct_tax_PSF',
        'ct_tax_PB',
        'ct_tax_OAD',
        'ct_total_tax_breakup',
        'ct_sub_total'
    ];

    public function ctInvoice(){
        return $this->belongsTo('App\CtInvoice');
    }
}
