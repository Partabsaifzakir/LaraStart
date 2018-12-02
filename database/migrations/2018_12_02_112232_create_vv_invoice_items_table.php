<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVvInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vv_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('vv_invoice_id');
            $table->text('vv_passenger_name');
            $table->text('vv_description');
            $table->decimal('vv_fares');
            $table->decimal('vv_sub_total');

            $table->foreign('vv_invoice_id')->references('id')->on('vv_invoices');

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
        Schema::dropIfExists('vv_invoice_items');
    }
}
