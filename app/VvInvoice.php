<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Vendor;
use App\VvInvoiceItems;

class VvInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'vendor_id',
        'vv_invoice_no',
        'vv_invoice_date',
        'vv_invoice_fares_total',
        'vv_invoice_grand_total'
    ];

    protected $with = [
        'vendor',
        'vvInvoiceItems',
    ];
    //
    public function vendor(){
        return $this->belongsTo('App\Vendor');
    }

    public function vvInvoiceItems(){
        return $this->hasMany('App\VvInvoiceItems');
    }
}
