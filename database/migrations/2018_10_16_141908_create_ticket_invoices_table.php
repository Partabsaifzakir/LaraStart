<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->Integer('vendor_id')->unsigned();
            $table->string('ticket_invoice_no')->unique();
            $table->date('ticket_invoice_date');
            $table->decimal('ticket_invoice_fares_total');


            $table->decimal('ticket_invoice_total_tax_SB');
            $table->decimal('ticket_invoice_total_tax_SRP');
            $table->decimal('ticket_invoice_total_tax_YQ');
            $table->decimal('ticket_invoice_total_tax_RG');
            $table->decimal('ticket_invoice_total_tax_PK');
            $table->decimal('ticket_invoice_total_tax_YR');
            $table->decimal('ticket_invoice_total_tax_SF');
            $table->decimal('ticket_invoice_total_tax_PTT');
            $table->decimal('ticket_invoice_total_tax_OAS');
            $table->decimal('ticket_invoice_total_tax_PSF');
            $table->decimal('ticket_invoice_total_tax_PB');
            $table->decimal('ticket_invoice_total_tax_OAD');


            $table->decimal('ticket_invoice_taxes_grand_total');
            $table->decimal('ticket_invoice_grand_total');
            $table->string('ticket_invoice_grand_total_words')->nullable();
            $table->string('ticket_invoice_terms')->nullable();

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
        Schema::dropIfExists('ticket_invoices');
    }
}
