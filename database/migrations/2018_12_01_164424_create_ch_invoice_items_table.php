<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ch_invoice_id');
            $table->text('ch_description');
            $table->decimal('ch_fares');
            $table->decimal('ch_sub_total');

            $table->foreign('ch_invoice_id')->references('id')->on('ch_invoices');

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
        Schema::dropIfExists('ch_invoice_items');
    }
}
