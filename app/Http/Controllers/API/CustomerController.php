<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use Image;
use Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::paginate(10);

        return $customer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'customer_name' => 'required|max:100|unique:customers',
            'customer_company_name' => 'max:100|unique:customers',
            'customer_contact' => 'required|numeric',
            'customer_type' => 'required',
            'customer_home_address' => 'required|max:200',
            'customer_office_address' => 'max:200',
            'customer_email' => 'required|string|email|max:100|unique:customers',
            'customer_date' => 'required'
        ]);

        return Customer::create([
            'customer_name' => $request ['customer_name'],
            'customer_company_name' => $request ['customer_company_name'],
            'customer_contact' => $request ['customer_contact'],
            'customer_type' => $request ['customer_type'],
            'customer_home_address' => $request ['customer_home_address'],
            'customer_office_address' => $request ['customer_office_address'],
            'customer_email' => $request ['customer_email'],
            'customer_date' => $request ['customer_date']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $this->validate($request,[
            'customer_name' => 'required|max:100|unique:customers,customer_name,'.$customer->id,
            'customer_company_name' => 'nullable|max:100|unique:customers,customer_company_name,'.$customer->id,
            'customer_contact' => 'required|numeric',
            'customer_type' => 'required',
            'customer_home_address' => 'required|max:200',
            'customer_office_address' => 'max:200',
            'customer_email' => 'required|string|email|max:100|unique:customers,customer_email,'.$customer->id,
            'customer_date' => 'required'
        ]);

        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return['message', 'Customer Deleted'];
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchCustomers()
    {
        if($search = \Request::get('q')){
            $customers = Customer::where(function($query) use ($search){
                $query->where('customer_company_name','LIKE',"%$search%")
                      ->orWhere('customer_email','LIKE',"%$search%")
                      ->orWhere('customer_type','LIKE',"%$search%");
                      
            })->paginate(10);
        }else{
            return Customer::paginate(10);
        }
        return $customers;
    }
}
