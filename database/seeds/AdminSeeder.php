<?php

use App\Profile;
use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create( [

        	'email'		=>	"admin@brandsoftinfotech.com",
        	'password'	=>	bcrypt( 'root123' ),
            'role_id'   =>  1,

        ] );

        $profile = Profile::create( [

            'user_id'   =>  $user->id,
        	'slug'	    =>	1,
       
        ] );
    }
}
