<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket_invoice_id');
            $table->string('passenger_name');
            $table->string('ticket_no');
            $table->string('flight_no');
            $table->date('departure_date');
            $table->string('sector');
            $table->decimal('tax_SB')->nullable();  //1 
            $table->decimal('tax_SRP')->nullable(); //2
            $table->decimal('tax_YQ')->nullable();  //3
            $table->decimal('tax_RG')->nullable();  //4
            $table->decimal('tax_PK')->nullable();  //5
            $table->decimal('tax_YR')->nullable();  //6
            $table->decimal('tax_SF')->nullable();  //7
            $table->decimal('tax_PTT')->nullable(); //8
            $table->decimal('tax_OAS')->nullable(); //9
            $table->decimal('tax_PSF')->nullable(); //10
            $table->decimal('tax_PB')->nullable();  //11
            $table->decimal('tax_OAD')->nullable(); //12
            $table->decimal('fares');
            $table->decimal('total_tax_breakup');
            $table->decimal('sub_total');

            $table->foreign('ticket_invoice_id')->references('id')->on('ticket_invoices');
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
        Schema::dropIfExists('ticket_invoice_items');
    }
}
