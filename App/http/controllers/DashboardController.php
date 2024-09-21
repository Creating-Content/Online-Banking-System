<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Statement;
use App\Accounts;
use DB;


class DashboardController extends Controller
{
    //

    public function dashboard(){
    	$user = User::find(auth()->id());
    	$my_account_id = $user->account->id;
    	
    	$balance = Statement::where('account_id',$my_account_id)->sum('balance');
    	$interest = ($balance * 1 * 3) / 100 ;
    	return view('dashboard',compact('user','interest','balance'));
    }

    public function adminDashboard(){
        $active_accounts = Accounts::where('status',1)->count();
           $deactive_accounts = Accounts::where('status',0)->count();
           $total_accounts = $active_accounts + $deactive_accounts;
           
           $transactions = Statement::with('account')
            ->selectRaw('account_id,SUM(abs(account_statements.balance)) as total')
            ->orderBy('total','desc')
            ->groupBy('account_statements.account_id')
            ->get();
      

    	return view('admin-dashboard',compact('active_accounts','deactive_accounts','total_accounts','transactions'));
    }
    
}
