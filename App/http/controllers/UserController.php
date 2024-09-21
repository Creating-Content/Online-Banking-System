<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\RegisterController;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Accounts;
use Session;
use App\Statement;


class UserController extends Controller
{
    public function index(){
    	//dd("this is user page");
    	$users = User::where('role',2)->get();
        	//$users= User::with('phone','account')->get();
    	return view('users',compact('users'));
    }
    public function viewUser(){
    	$p = User::all();
    	return view('users',compact('users'));
    }
    
    public function profile($id,Request $request){
        $rate=3;
    	$users=User::find($id);
    	//dd($users);
    	$interest = ($users->account->balance * 1 * 3) / 100 ;
        $account = Accounts::where('user_id',$id)->first();
        $balance = Statement::where('account_id',$account->id)->sum('balance');
        // $CI = $balance * (pow((1 + $rate / 100), $t));
        $from = isset($request->from) ? $request->from: '';
        $to = isset($request->to) ? $request->to : '';

        $statements = [];

        if($from != '' || $to != ''){
             $statements = Statement::where('account_id',$account->id);
            if($from != '' && $to != ''){

                $statements = $statements
                ->whereDate('created_at','>=',$from)
                ->whereDate('created_at','<=',$to);

             }elseif($from != '' && $to == ''){

                $statements = $statements->whereDate('created_at','>=',$from);

             }elseif($to != '' && $from == ''){

                 $statements = $statements->whereDate('created_at','<=',$to);

             }
             $statements = $statements->get();
             
        }
        // $statements = Statement::where('account_id',$account->id)->get();
    	return view('profile',compact('users','interest','balance','statements'));


    }

    public function addUser(){
        return view('adduser');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'gender' => 'required',
            'phone' => ['required', 'digits:10'],
            'dob' => 'required',
            // 'acc_num' => 'required',
            'acc_type' =>'required',
            'address' => 'required'
        ]);
        $data = $request->all();
    try{
            $previous_account= Accounts::latest()->first();
            if(empty($previous_account)){
                $account_number = 'NBB001';
            }else{
                $account_number = 'NBB00'.($previous_account->id + 1);
            }
          
            $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_type' => $data['acc_type'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone'],
            'dob' => $data['dob'],
            'account_number' => $account_number,
            'address' => $data['address']
            ]);
            $account = new Accounts();
            $account->username = $data['name'];
            $account->account_type = $data['acc_type'];
            $account->password = $data['password'];
            $account->user_id = $user->id;
            $account->bank = 'Nepal Bangladesh Bank Pvt. Ltd.';
            $account->account_number = $account_number;
            $account->balance = 0;
            $account->save();
            Session::flash('message','User Added Successfully');
            return redirect()->route('user.list');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }  
    }

    public function update(Request $request,$user_id){
    //    dd($request->all());
        $user = User::find($user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
         $user->save();
        $account = Accounts::where('user_id',$user_id)->first();
      //  dd($account);
        if($account){
              $account->status = $request->status;
              $account->save();
        }
      //  $account->username = $request->name;
      //  $account->email = $request->email;
      

        Session::flash('message','User Updated Successfully');
        return redirect()->route('user.list');
    }
    public function edit($user_id){
      //  dd($user_id);
        $user = User::find($user_id);
        //dd($user);
        return view('updateuser',compact('user'));
    }

    
    
}
