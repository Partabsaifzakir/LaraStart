<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Vendor;
use Image;
use Gate;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor = Vendor::paginate(10);

        return $vendor;
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
            'vendor_company_name' => 'required|string|max:100|unique:vendors',
            'vendor_address' => 'required|max:200',
            'vendor_contact' => 'required|numeric',
            'vendor_person_name' => 'required|string|max:100',
            'vendor_email' => 'required|string|max:100|unique:vendors'
        ]);

        return Vendor::create([
            'vendor_company_name' => $request['vendor_company_name'],
            'vendor_address' => $request['vendor_address'],
            'vendor_contact' => $request['vendor_contact'],
            'vendor_person_name' => $request['vendor_person_name'],
            'vendor_email' => $request['vendor_email'],
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
        $vendor = Vendor::findOrFail($id);

        $this->validate($request,[
            'vendor_company_name' => 'required|string|max:100|unique:vendors,vendor_company_name,'.$vendor->id,
            'vendor_address' => 'required|max:200',
            'vendor_contact' => 'required|numeric',
            'vendor_person_name' => 'required|string|max:100',
            'vendor_email' => 'required|string|max:100|unique:vendors,vendor_email,'.$vendor->id
        ]);

        $vendor->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->delete();

        return['message', 'Vendor Deleted'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchVendors()
    {
        if($search = \Request::get('q')){
            $vendors = Vendor::where(function($query) use ($search){
                $query->where('vendor_company_name','LIKE',"%$search%")
                      ->orWhere('vendor_email','LIKE',"%$search%")
                      ->orWhere('vendor_person_name','LIKE',"%$search%");
                      
            })->paginate(10);
        }else{
            return Vendor::paginate(10);
        }
        return $vendors;
    }
}
