<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\CvInvoice;

class CvInvoiceItems extends Model
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'cv_invoice_id',
        'cv_passenger_name',
        'cv_description',
        'cv_fares',
        'cv_sub_total'
    ];

    public function cvInvoice(){
        return $this->belongsTo('App\CvInvoice');
    }
}
