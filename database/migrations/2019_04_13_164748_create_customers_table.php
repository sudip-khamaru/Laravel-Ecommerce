<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string( 'billing_first_name' );
            $table->string( 'billing_last_name' );
            $table->string( 'billing_username' );
            $table->string( 'billing_email' );
            $table->string( 'billing_address1' );
            $table->string( 'billing_address2' );
            $table->string( 'billing_country' );
            $table->string( 'billing_state' );
            $table->string( 'billing_zip' );
            $table->string( 'shipping_first_name' )->nullable();
            $table->string( 'shipping_last_name' )->nullable();
            $table->string( 'shipping_address1' )->nullable();
            $table->string( 'shipping_address2' )->nullable();
            $table->string( 'shipping_country' )->nullable();
            $table->string( 'shipping_state' )->nullable();
            $table->string( 'shipping_zip' )->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
