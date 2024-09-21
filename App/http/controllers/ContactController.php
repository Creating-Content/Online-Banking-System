<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function contact(){
    	//dd('this is index page');
    	return view('contactus');
    }
}
