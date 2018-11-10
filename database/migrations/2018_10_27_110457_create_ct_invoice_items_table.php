<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ct_invoice_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ct_invoice_id');
            $table->string('ct_passenger_name');
            $table->string('ct_ticket_no');
            $table->string('ct_flight_no');
            $table->date('ct_departure_date');
            $table->string('ct_sector');
            $table->decimal('ct_tax_SB')->nullable();  //1 
            $table->decimal('ct_tax_SRP')->nullable(); //2
            $table->decimal('ct_tax_YQ')->nullable();  //3
            $table->decimal('ct_tax_RG')->nullable();  //4
            $table->decimal('ct_tax_PK')->nullable();  //5
            $table->decimal('ct_tax_YR')->nullable();  //6
            $table->decimal('ct_tax_SF')->nullable();  //7
            $table->decimal('ct_tax_PTT')->nullable(); //8
            $table->decimal('ct_tax_OAS')->nullable(); //9
            $table->decimal('ct_tax_PSF')->nullable(); //10
            $table->decimal('ct_tax_PB')->nullable();  //11
            $table->decimal('ct_tax_OAD')->nullable(); //12
            $table->decimal('ct_fares');
            $table->decimal('ct_total_tax_breakup');
            $table->decimal('ct_sub_total');

            $table->foreign('ct_invoice_id')->references('id')->on('ct_invoices');

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
        Schema::dropIfExists('ct_invoice_items');
    }
}
