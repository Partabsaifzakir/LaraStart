<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\ChInvoice;

class ChInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'ch_invoice_id',
        'ch_description',
        'ch_fares',
        'ch_sub_total'
    ];

    public function chInvoice(){
        return $this->belongsTo('App\ChInvoice');
    }
}
