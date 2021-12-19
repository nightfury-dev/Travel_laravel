<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryCustomerInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('itinerary_customer_information')) {
            Schema::create('itinerary_customer_information', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('itinerary_id');
                $table->longtext('customer_infomation');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itinerary_customer_information');
    }
}
