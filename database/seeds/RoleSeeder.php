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

        	'name'			=>	"Admin",
        	'description'	=>	"Admin Role",

        ] );

        $role = Role::create( [

        	'name'			=>	"Customer",
        	'description'	=>	"Customer Role",

        ] );
    }
}
