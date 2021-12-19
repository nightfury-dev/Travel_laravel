<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('itinerary')) {
            Schema::create('itinerary', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 255);
                $table->string('reference_number', 255);
                $table->integer('enquiry_id');
                $table->string('from_email', 255)->nullable();
                $table->string('to_email', 255)->nullable();
                $table->string('attach_file', 255)->nullable();
                $table->longText('email_template')->nullable();
                $table->integer('created_id');
                $table->integer('updated_id')->nullable();
                $table->integer('travel_number')->nullable();
                $table->string('budget', 255)->nullable();
                $table->integer('margin_price')->nullable();
                $table->integer('currency')->nullable();
                $table->date('from_date');
                $table->date('to_date');
                $table->integer('adult_number');
                $table->integer('children_number');
                $table->integer('single_count');
                $table->integer('double_count');
                $table->integer('twin_count');
                $table->integer('triple_count');
                $table->integer('family_count');
                $table->integer('status');
                $table->longText('note')->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists('itinerary');
    }
}
