<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create( [

        	'name'			=>	"admin",
        	'description'	=>	"Admin Role",

        ] );

        $role = Role::create( [

        	'name'			=>	"customer",
        	'description'	=>	"Customer Role",

        ] );
    }
}
