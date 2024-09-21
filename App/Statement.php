<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
	protected $table = 'account_statements';
    protected $guarded = [];

     public function account(){
    	return $this->belongsTo('App\Accounts','account_id');
    }
}
