<?php

use App\Profile;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        	'password'	=>	Hash::make( "root123" ),
            'role_id'   =>  1,

        ] );

        $profile = Profile::create( [

            'user_id'   =>  $user->id,
            'name'      =>  "Admin",
        	'slug'	    =>	"admin",
            'thumbnail' =>  "images/no-thumbnail.jpeg",
       
        ] );
    }
}
