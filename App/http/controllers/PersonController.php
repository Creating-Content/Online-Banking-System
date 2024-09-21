<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Address;
use Session;



class PersonController extends Controller
{
    public function add(){
    	//$addperson = Person::all();
    	return view('addperson');
    }
    public function store(Request $request){
        dd($request->all());
        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'dob'=>'required',
        ]);
    	$person = new Person;
    	$person->first_name =$request->first_name;
    	$person->last_name = $request->last_name;
        $person->gender=$request->gender;
    	$person->dob = $request->dob;
    	$person->save();
        $address = new Address;
        $address->tole =$request->tole;
        $address->ward_no = $request->ward_no;
        $address->municipality = $request->municipality;
        $address->district =$request->district;
        $address->zone = $request->zone;
        $address->state=$request->state;
        $address->country = $request->country;
        $address->person_id = $person->id;
        $address->save();


        Session::flash('message','Added Successfully');
        return redirect()->route('list.person');


    }
    public function update(Request $request,$person_id){
    	//dd($request->all(),$person_id);
    	$person = Person::find($person_id);

    	$person->first_name = $request->first_name;
    	$person->last_name = $request->last_name;
    	$person->dob = $request->dob;
    	$person->save();
        Session::flash('message','Updated Successfully');
    	return redirect()->route('list.person');
    }

    public function edit($person_id){
    //	$person = Person::where('id',$person_id)->first();
    	$person = Person::find($person_id);


    	//$editperson = Person::all();
    	return view('editperson',compact('person'));
    }
    public function list(){
    	$person = Person::with('address')->get();
    	//dd("done");
    	return view('listperson',compact('person'));
    }

    public function delete($person_id){
        $person=Person::find($person_id);
        $person->delete();
        Session::flash('message','Deleted Successfully');
        return redirect()->back();
    }
}
