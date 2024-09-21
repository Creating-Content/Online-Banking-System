<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Accounts;
use Illuminate\Auth\Events\Registered;
use Session;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data,
            [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'gender' => 'required',
            'phone' => ['required', 'digits:10'],
            'dob' => 'required',
            'acc_type' =>'required',
            'address' => 'required'
        ]
        //  [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:6', 'confirmed'],

        // ]
    );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       // dd($data);
        DB::beginTransaction();

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
         $account->account_number = $account_number;
         $account->balance = 0;
         $account->save();
         DB::commit();

         return $user;



        }catch(\Exception $e){
         DB::rollback();

            dd($e->getMessage());
        }
         

    }
    public function register(Request $request)
{
    $this->validator($request->all())->validate();

    event(new Registered($user = $this->create($request->all())));
   /* Session::flash('message','Registration successful. Please login to continue.')
*/
    return redirect($this->redirectPath())->with('msg','Registration successful. Please login to continue.');
}
    
}
