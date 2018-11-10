<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ct_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('customer_id')->unsigned();
            $table->string('ct_invoice_no')->unique();
            $table->date('ct_invoice_date');
            $table->decimal('ct_invoice_fares_total');
            $table->decimal('ct_invoice_taxes_grand_total');
            $table->decimal('ct_invoice_grand_total');
            $table->string('ct_invoice_grand_total_words')->nullable();
            $table->string('ct_invoice_terms')->nullable();

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
        Schema::dropIfExists('ct_invoices');
    }
}
