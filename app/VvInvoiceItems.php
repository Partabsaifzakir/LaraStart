<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\VvInvoice;

class VvInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'vv_invoice_id',
        'vv_passenger_name',
        'vv_description',
        'vv_fares',
        'vv_sub_total'
    ];

    public function vvInvoice(){
        return $this->belongsTo('App\VvInvoice');
    }
}
