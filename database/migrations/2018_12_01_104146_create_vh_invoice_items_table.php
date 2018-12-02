<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVhInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vh_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('vh_invoice_id');
            $table->text('vh_description');
            $table->decimal('vh_fares');
            $table->decimal('vh_sub_total');

            $table->foreign('vh_invoice_id')->references('id')->on('vh_invoices');

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
        Schema::dropIfExists('vh_invoice_items');
    }
}
