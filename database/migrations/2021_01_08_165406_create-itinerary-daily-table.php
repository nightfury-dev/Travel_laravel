<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('itinerary_daily')) {
            Schema::create('itinerary_daily', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('itinerary_id');
                $table->integer('product_id');
                $table->string('product_price_tag', 255);
                $table->string('product_price_season', 255);
                $table->string('product_price_currency', 255);
                $table->string('product_price_id', 255);
                $table->integer('itinerary_margin_type')->nullable();
                $table->integer('itinerary_margin_price')->nullable();
                $table->string('date');
                $table->string('start_time');
                $table->string('end_time');
                $table->integer('adults_num');
                $table->integer('children_num');
                // $table->softDeletes();
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
        //
        Schema::dropIfExists('itinerary_daily');
    }
}
