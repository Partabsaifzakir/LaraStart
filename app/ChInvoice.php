<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\ChInvoiceItems;

class ChInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'customer_id',
        'ch_invoice_no',
        'ch_invoice_date',
        'ch_invoice_fares_total',
        'ch_invoice_grand_total'
    ];

    protected $with = [
        'customer',
        'chInvoiceItems',
    ];
    //
    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function chInvoiceItems(){
        return $this->hasMany('App\ChInvoiceItems');
    }
}
