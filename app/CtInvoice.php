<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Customer;
use App\CtInvoiceItems;

class CtInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'customer_id',
        'ct_invoice_no',
        'ct_invoice_date',
        'ct_invoice_fares_total',
        'ct_invoice_taxes_grand_total',
        'ct_invoice_grand_total',
        'ct_invoice_grand_total_words',
        'ct_invoice_terms'
    ];

    protected $with = [
        'customer',
        'ctInvoiceItems',
    ];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function ctInvoiceItems(){
        return $this->hasMany('App\CtInvoiceItems');
    }
}
