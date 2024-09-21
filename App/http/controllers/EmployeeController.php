<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Accounts;
use App\User;
use Session;
use Illuminate\Support\Facades\Input;

class EmployeeController extends Controller
{
    //
    public function add(){
    	//$addperson = Person::all();
    	return view('addemployee');
    }
    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'post'=>'required',
            'image'=>'required'
        ]);
        $employee = new Employee();
    	$employee->name =$request->name;
    	$employee->email = $request->email;
    	$employee->post = $request->post;

    	if(Input::hasfile('image'))
        {
        	
            $file=Input::file('image');
            $destinationPath='public/images';
            $file->move($destinationPath,$file->getClientOriginalName());
            $employee->image=$file->getClientOriginalName();
        
        }
    	$employee->save();
        Session::flash('message','Added Successfully');
        return redirect()->route('list.employee');
}
         public function list(){
     	$employee = Employee::all();

     	// $accounts = User::leftJoin('accounts','accounts.user_id','=','users.id')->where('accounts.user_id',1)->first();
     	// dd($accounts);
     	//$accounts = Accounts::find(1)->users;
    // 	dd($accounts);

     //	$user = User::find(2)->account;
     	//dd($user);
     	$user = User::with('account')->get();
     	//dd($user->account->account_number);
    	return view('listemployee',compact('employee'));
    }

}
