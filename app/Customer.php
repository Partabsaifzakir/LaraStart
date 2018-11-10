<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\CtInvoice;
use App\CtInvoiceItems;

class Customer extends Model
{
    use Notifiable, HasApiTokens;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'customer_company_name',
        'customer_contact',
        'customer_type',
        'customer_home_address',
        'customer_office_address',
        'customer_email',
        'customer_date'             
    ];

    public function ctInvoices(){
        return $this->hasMany('App\CtInvoice');
    }
}
