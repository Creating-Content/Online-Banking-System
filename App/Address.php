<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table='addresses';
    
    public function person(){
    	return $this->belongsTo('App\Person','id');
    }
}
