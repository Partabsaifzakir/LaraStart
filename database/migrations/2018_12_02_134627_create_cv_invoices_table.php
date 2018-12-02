<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('customer_id')->unsigned();
            $table->string('cv_invoice_no')->unique();
            $table->date('cv_invoice_date');
            $table->decimal('cv_invoice_fares_total');
            $table->decimal('cv_invoice_grand_total');

            $table->foreign('customer_id')->references('id')->on('customers');

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
        Schema::dropIfExists('cv_invoices');
    }
}
