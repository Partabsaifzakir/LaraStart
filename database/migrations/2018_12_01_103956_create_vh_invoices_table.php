<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('vendor_id')->unsigned();
            $table->string('vh_invoice_no')->unique();
            $table->date('vh_invoice_date');
            $table->decimal('vh_invoice_fares_total');
            $table->decimal('vh_invoice_grand_total');

            $table->foreign('vendor_id')->references('id')->on('vendors');

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
        Schema::dropIfExists('vh_invoices');
    }
}
