<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{   
    use Notifiable, HasApiTokens;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_company_name','vendor_address','vendor_contact','vendor_person_name','vendor_email'
    ];
}
