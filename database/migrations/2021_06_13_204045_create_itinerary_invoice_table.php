<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itinerary_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('itinerary_id');
            $table->string('title', 255);
            $table->string('invoice_number', 255);
            $table->date('issue_date');
            $table->date('due_date');
            $table->integer('quantitiy');
            $table->integer('price');
            $table->integer('tax');
            $table->integer('payment_type');
            $table->integer('status');
            $table->string('voucher', 255);
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
        Schema::dropIfExists('itinerary_invoice');
    }
}