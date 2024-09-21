<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    //
    protected $table='persons';
   // protected $fillable=['first_name','last_name','dob'];
    public function address(){
    	return $this->hasOne('App\Address');
    }
    
}
