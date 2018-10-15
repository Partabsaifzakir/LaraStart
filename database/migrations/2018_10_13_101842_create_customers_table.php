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

            $table->string('customer_name');
            $table->string('customer_company_name')->nullable();
            $table->string('customer_contact');
            $table->string('customer_type');
            $table->string('customer_home_address');
            $table->string('customer_office_address')->nullable();
            $table->string('customer_email');
            $table->date('customer_date');

            $table->timestamps();
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
