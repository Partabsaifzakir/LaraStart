<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\CvInvoiceItems;

class CvInvoice extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'customer_id',
        'cv_invoice_no',
        'cv_invoice_date',
        'cv_invoice_fares_total',
        'cv_invoice_grand_total'
    ];

    protected $with = [
        'customer',
        'cvInvoiceItems',
    ];
    //
    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function cvInvoiceItems(){
        return $this->hasMany('App\CvInvoiceItems');
    }
}
