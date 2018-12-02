<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\VhInvoice;

class VhInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'vh_invoice_id',
        'vh_description',
        'vh_fares',
        'vh_sub_total'
    ];

    public function vhInvoice(){
        return $this->belongsTo('App\VhInvoice');
    }
}
