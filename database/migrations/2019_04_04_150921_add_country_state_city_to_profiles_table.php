<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryStateCityToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            
            $table->unsignedInteger( 'country_id' )->after( 'address' )->nullable();
            $table->unsignedInteger( 'state_id' )->after( 'country_id' )->nullable();
            $table->unsignedInteger( 'city_id' )->after( 'state_id' )->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            
            $table->dropColumn( 'city_id' );
            $table->dropColumn( 'state_id' );
            $table->dropColumn( 'country_id' );

        });
    }
}
