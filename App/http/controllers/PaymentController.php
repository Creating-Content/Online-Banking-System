<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use DB;
use App\User;
use App\Accounts;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Payment;
use App\Statement;

class PaymentController extends Controller
{
    
    public function storeBT(Request $request){
        $this->validate($request,[
            'amt' => 'required',
            'bank' => 'required',
            'acc_no' => 'required',
            'acc_name' => 'required',
            'purpose' => 'required',
         ]);
        // dd($request);
        $data = $request->all();

        $user = Auth::id();
        $username = Auth::user()->name;
       // dd($username);
        $account = Accounts::where('username',$username)->first();
       
        $balance = Statement::where('account_id',$account->id)->sum('balance');
        //dd($balance);
        if($balance < $data['amt']){
             Session::flash('error','The selected account doesnot have enough balance.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try{
            $transaction = new Transaction();
            $transaction->amount = -$data['amt'];
            $transaction->to_bank = $data['bank'];
            $transaction->to_account_number = $data['acc_no'];
            $transaction->to_account_name = $data['acc_name'];
            $transaction->purpose = $data['purpose'];
            $transaction->user_id = $user;
            $transaction->account_id = $account->id;
            $transaction->transaction_type = $data['transaction_type'];
            $transaction->save();


            $statement = new Statement();
            $statement->account_id = $account->id;
            $statement->balance = -$data['amt'];
            $statement->description = $data['purpose'];
            $statement->save();

            $check_account = Accounts::where('account_number',$data['acc_no'])
            ->where('bank',$data['bank'])
            ->first();
           
            if(!empty($check_account)){

                $transaction = new Transaction();
                $transaction->amount = -$data['amt'];
                $transaction->to_bank = $data['bank'];
                $transaction->to_account_number = $data['acc_no'];
                $transaction->to_account_name = $data['acc_name'];
                $transaction->purpose = $data['purpose'];
                $transaction->user_id = $check_account->user_id;
                $transaction->account_id = $check_account->id;
                $transaction->transaction_type = $data['transaction_type'];
                $transaction->save();
                

                $statement1 = new Statement();
                $statement1->account_id = $check_account->id;
                $statement1->balance = $data['amt'];
                $statement1->description = $data['purpose'];
                $statement1->save();
             }


            DB::commit();
           Session::flash('message','Transaction Made Successfully');
            return redirect()->route('user.dashboard');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }


    public function storeTopUp(Request $request){
        $this->validate($request,[
            'num' => 'required',
            'amt' => 'required',
            'purpose' => 'required',
         ]);
        // dd($request);
        $data = $request->all();
        $user = Auth::id();
        //dd($account_id);
        $account = Accounts::where('username',Auth::user()->name)->first();
        $balance = Statement::where('account_id',$account->id)->sum('balance');
        //dd($balance);
        if($balance < $data['amt']){
             Session::flash('error','The selected account doesnot have enough balance.');
            return redirect()->back();
        }
       // dd($account);
        DB::beginTransaction();
        try{
            $transaction = new Transaction();
            $transaction->amount = -$data['amt'];
            $transaction->to_account_number = $data['num'];
            $transaction->purpose = $data['purpose'];
            $transaction->user_id = $user;
            $transaction->account_id = $account->id;
            $transaction->transaction_type = $data['transaction_type'];
            $transaction->save();

            $statement = new Statement();
            $statement->account_id = $account->id;
            $statement->balance = -$data['amt'];
            $statement->description = $data['purpose'];
            $statement->save();
            DB::commit();
            Session::flash('message','Top-up Made Successfully');
            return redirect()->route('user.dashboard');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }

    public function storeUP(Request $request){
        // dd($request->all());
        $this->validate($request,[
            'utility' => 'required',
            'u_name' => 'required',
            'amt' => 'required',
            'purpose' => 'required',
         ]);
        // dd('11');
         //dd($request->all());
        $data = $request->all();
        $user = Auth::id();
        $account = Accounts::where('username',Auth::user()->name)->first();
        $balance = Statement::where('account_id',$account->id)->sum('balance');
        //dd($balance);
        if($balance < $data['amt']){
             Session::flash('error','The selected account doesnot have enough balance.');
            return redirect()->back();
        }
       // dd($account_id);
        DB::beginTransaction();
        try{
            $transaction = new Transaction();
            $transaction->amount = -$data['amt'];
            $transaction->to_account_number = $data['u_name'];
            $transaction->purpose = $data['purpose'];
            $transaction->user_id = $user;
            $transaction->account_id = $account->id;
            $transaction->transaction_type = 'Utility Payment';
            $transaction->save();

            $payment = new Payment();
            $payment->transaction_id = $transaction->id;
            $payment->payment_amount = $data['amt'];
            $payment->payment_type = $data['payment_type'];
            $payment->save();

            $statement = new Statement();
            $statement->account_id = $account->id;
            $statement->balance = -$data['amt'];
            $statement->description = $data['purpose'];
            $statement->save();
            DB::commit();
            Session::flash('message','Payment Made Successfully');
            return redirect()->route('user.dashboard');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }


    public function storeWP(Request $request){
        $this->validate($request,[
            'num' => 'required',
            'amt' => 'required',
            'purpose' => 'required',
         ]);
        // dd($request);
        $data = $request->all();
        $user = Auth::id();
        $account = Accounts::where('username',Auth::user()->name)->first();
        $balance = Statement::where('account_id',$account->id)->sum('balance');
        //dd($balance);
        if($balance < $data['amt']){
             Session::flash('error','The selected account doesnot have enough balance.');
            return redirect()->back();
        }
        //dd($account_id);
        $payment->payment_type = $data['payment_type'];
        DB::beginTransaction();
        try{
            $transaction = new Transaction();
            $transaction->amount = -$data['amt'];
            $transaction->to_account_number = $data['num'];
            $transaction->purpose = $data['purpose'];
            $transaction->user_id = $user;
            $transaction->account_id = $account->id;
            $transaction->transaction_type = $data['transaction_type'];
            $transaction->save();

            $payment = new Payment();
            $payment->transaction_id = $transaction->id;
            $payment->payment_amount = $data['amt'];
            $payment->payment_type = $data['payment_type'];
            $payment->save();

            $statement = new Statement();
            $statement->account_id = $account->id;
            $statement->balance = -$data['amt'];
            $statement->description = $data['purpose'];
            $statement->save();
            DB::commit();
            Session::flash('message','Wallet Payment Made Successfully');
            return redirect()->route('user.dashboard');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }

        

    }

    public function deposit(Request $request){

        $this->validate($request,[
            'amt' => 'required',
            'account_id' => 'required',
            'description' => 'required'
        ]);
        $data = $request->all();
       
        $account = Accounts::where('id',$data['account_id'])->first();

        DB::beginTransaction();
        try{
            $statement = new Statement();
            $statement->account_id = $data['account_id'];
            $statement->balance = $data['amt'];
            $statement->description = $data['description'];
            $statement->save();

            $transaction = new Transaction();
            $transaction->amount = $data['amt'];
            $transaction->account_id = $data['account_id'];
            $transaction->purpose = $data['description'];
            $transaction->user_id = $account->user_id;
            $transaction->transaction_type = $data['deposit'];
            $transaction->to_account_number = $account->account_number;
            $transaction->to_account_name = 'self';
            $transaction->to_bank = 'Nepal Bangladesh Bank Pvt. Ltd.';
            $transaction->save();
            DB::commit();
            Session::flash('message','Deposit Made Successfully');
            return redirect()->route('user.list');
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }

}
