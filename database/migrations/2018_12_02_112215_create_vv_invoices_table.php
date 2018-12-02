<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVvInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vv_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('vendor_id')->unsigned();
            $table->string('vv_invoice_no')->unique();
            $table->date('vv_invoice_date');
            $table->decimal('vv_invoice_fares_total');
            $table->decimal('vv_invoice_grand_total');

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
        Schema::dropIfExists('vv_invoices');
    }
}
