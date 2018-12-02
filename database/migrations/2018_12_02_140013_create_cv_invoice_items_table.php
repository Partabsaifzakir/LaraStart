<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('cv_invoice_id');
            $table->text('cv_passenger_name');
            $table->text('cv_description');
            $table->decimal('cv_fares');
            $table->decimal('cv_sub_total');

            $table->foreign('cv_invoice_id')->references('id')->on('cv_invoices');

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
        Schema::dropIfExists('cv_invoice_items');
    }
}
