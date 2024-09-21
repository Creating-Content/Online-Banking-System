<?php

use Illuminate\Database\Seeder;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
	            'name' => 'Admin',
	            'email' => 'admin@gmail.com',
	            'password' => bcrypt('admin123'),
	            'gender' => 'male',
	            'dob' => '1998-07-12',
	            'phone_number' => '9860907300',
	            'address' => 'kapan'
	        ]);
    }
}
