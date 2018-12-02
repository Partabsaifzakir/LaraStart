<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\VhInvoiceItems;

class VhInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'vendor_id',
        'vh_invoice_no',
        'vh_invoice_date',
        'vh_invoice_fares_total',
        'vh_invoice_grand_total'
    ];

    protected $with = [
        'vendor',
        'vhInvoiceItems',
    ];
    //
    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }

    public function vhInvoiceItems(){
        return $this->hasMany('App\VhInvoiceItems');
    }
}
