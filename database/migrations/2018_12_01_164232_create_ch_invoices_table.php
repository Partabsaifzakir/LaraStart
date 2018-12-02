<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('customer_id')->unsigned();
            $table->string('ch_invoice_no')->unique();
            $table->date('ch_invoice_date');
            $table->decimal('ch_invoice_fares_total');
            $table->decimal('ch_invoice_grand_total');

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
        Schema::dropIfExists('ch_invoices');
    }
}
