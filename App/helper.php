<?php 
use App\User; 

function current_user_role(){
$user = User::find(auth()->user()->id);

if(!empty($user)){
	return $user->role;
}

}