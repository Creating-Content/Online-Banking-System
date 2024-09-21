<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () { 
Route::get('/sign-out', 'HomeController@logout')->name('logout.user');
// Route::get('/contact-us','ContactController@contact')->name('contact.index');
Route::group(['middleware' => ['admin.role']], function () { 

Route::get('/users','UserController@index')->name('user.list'); 
Route::get('/admin-dashboard','DashboardController@adminDashboard')->name('admin.dashboard');
Route::get('profile/{id}','UserController@profile')->name('user.profile');
Route::get('/add-user','UserController@addUser')->name('add.user');
Route::post('/store-user','UserController@store')->name('store.user');
Route::get('/user/edit/{id}','UserController@edit')->name('edit.user');
Route::post('/update-user/{id}','UserController@update')->name('update.user');
Route::get('/deposit','BankController@deposit')->name('make.deposit');
Route::post('/account-deposit','PaymentController@deposit')->name('account.deposit');



});

Route::group(['middleware' => ['user.role']], function () { 

Route::get('/dashboard','DashboardController@dashboard')->name('user.dashboard');
Route::get('/bank-transfer', 'BankController@fundTransfer')->name('bank.transfer');
Route::get('/top-up', 'BankController@topUp')->name('topup');
Route::get ('/utilities-payment','BankController@utilitiesPayment')->name('utilities.payment');
Route::get('/wallet-payment','BankController@walletPayment')->name('wallet.payment');

Route::post('/bank-transfer','PaymentController@storeBT')->name('fund.transfer');
Route::post('/topup-store','PaymentController@storeTopUp')->name('store.topup');
Route::post('/utility-payment','PaymentController@storeUP')->name('store.utility-payment');
Route::post('/wallet-payment-store','PaymentController@storeWP')->name('store.wallet-payment');
Route::get('/transaction','BankController@viewTransaction')->name('view.transaction');
Route::get('/transaction/list','PaymentController@viewTransaction')->name('transaction.list');
Route::get('/statements','BankController@statement')->name('view.statement');

});
// Route::get('/person/add','PersonController@add')->name('add.person');
// Route::post('/person/store','PersonController@store')->name('store.person');
// Route::get('/person/edit/{id}','PersonController@edit')->name('edit.person');
// Route::post('/person/update/{id}','PersonController@update')->name('update.person');
// Route::get('/persons','PersonController@list')->name('list.person');
// Route::get('/person/delete/{id}','PersonController@delete')->name('delete.person');
// Route::get('/address/add','AddressController@add')->name('add.address');
// Route::post('/address/store','AddressController@store')->name('store.address');
// Route::get('/addresses','AddressController@list')->name('list.address');
// Route::get('/address/edit/{id}','AddressController@edit')->name('edit.address');
// Route::post('/address/update/{id}','AddressController@update')->name('update.address');

//Route::get('/users/list','UserController@viewUsers')->name('list.users');

//Route::get('/address/delete/{id}','AddressController@delete')->name('delete.address');

// Route::get('/register','Auth\RegisterController@create')->name('user.register');



// Route::get('/employee','EmployeeController@add')->name('add.employee');
// Route::post('/employee/store','EmployeeController@store')->name('store.employee');
// Route::get('/employees','EmployeeController@list')->name('list.employee');
   
});

Route::get('/home', 'HomeController@index')->name('home');

//Route::post('/register'.'HomeController@register')->name('register.user');


