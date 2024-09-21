<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Accounts extends Model
{
    //
    protected $table= 'accounts';
    protected $guarded = [];

    public function users(){
    	return $this->belongsTo('App\User','user_id');
    }

    public function statement(){
    	return $this->hasMany('App\Statement','account_id');
    }

   
    


}
