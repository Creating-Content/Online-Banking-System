<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use Session;

class AddressController extends Controller
{
	public function add(){
    	return view('addaddress');
    }
    public function store(Request $request){

        $this->validate($request,[
            'tole'=>'required',
            'ward_no'=>'required',
            'municipality'=>'required',
            'district'=>'required',
            'zone'=>'required',
            'country'=>'required',
        ]);
    	$address = new Address;
    	$address->tole =$request->tole;
    	$address->ward_no = $request->ward_no;
    	$address->municipality = $request->municipality;
    	$address->district =$request->district;
    	$address->zone = $request->zone;
    	$address->country = $request->country;
    	$address->save();
Session::flash('message','Added successfully');
      
    	return redirect()->route('list.address');
    }

    public function update(Request $request, $address_id){
        $address = Address::find($address_id);
        $address->tole=$request->tole;
        $address->ward_no=$request->ward_no;
        $address->municipality=$request->municipality;
        $address->district=$request->district;
        $address->zone=$request->zone;
        $address->country=$request->country;
        $address->save();
          Session::flash('message','Updated successfully');
      
        return redirect()->route('list.address');
    }

    public function edit($address_id){
        $address = Address::find($address_id);
        return view('editaddress',compact('address'));
    }
    public function list(){
    	$address = Address::orderBy('id','desc')->get();
    	//dd($address);
    	return view('listaddress',compact('address'));
    }
    public function delete($address_id){

        $address = Address::find($address_id);
        $address->delete();
        Session::flash('message','Deleted Successfully');
        return redirect()->back();

    }
    
}
