<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Accounts;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use App\Statement;

class BankController extends Controller
{
    public function fundTransfer(){
    	return view('banktransfer');
    }
    public function topUp(){
    	return view("top-up");
    }
    public function utilitiesPayment(){
    	return view("utilitiespayment");
    }
    public function walletPayment(){
    	return view("walletpayment");
    }
    public function transaction(){
    	return view("transaction");
    }

    public function viewTransaction(){

        $transaction=Transaction::query();

        if(Auth::user()->role == 2){

            $transaction = $transaction->where('user_id',Auth::user()->id);
        }

        $transaction = $transaction->get();

        return view('transaction',compact('transaction'));
    }
    public function deposit(){
        $accounts=Accounts::whereHas('users', function(Builder $q){
             $q->where('role','=',2);
        })->get();

         // $accounts=Accounts::whereRelation('users', 'role','=',2)->get();

        return view('loadbalance',compact('accounts'));

    }

    public function statement(Request $request){
        
        $from = isset($request->from) ? $request->from: '';
        $to = isset($request->to) ? $request->to : '';
        // dd($from,$to);

        $account = Accounts::where('user_id', Auth::user()->id)->first();
       
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
        // dd(empty($statements));

        return view('statement',compact('statements'));
    }
}

