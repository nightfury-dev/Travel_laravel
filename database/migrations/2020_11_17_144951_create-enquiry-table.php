<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('enquiry')) {
            Schema::create('enquiry', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 255);
                $table->string('reference_number', 255);
                $table->integer('customer_id');
                $table->integer('staff_id');
                $table->integer('travel_number')->nullable();
                $table->integer('budget');
                $table->date('from_date')->nullable();
                $table->date('to_date')->nullable();
                $table->integer('adult_number');
                $table->integer('children_number');
                $table->integer('infant_number');
                $table->integer('single_count');
                $table->integer('double_count');
                $table->integer('twin_count');
                $table->integer('triple_count');
                $table->integer('family_count');
                $table->integer('budget_per_total');
                $table->tinyInteger('is_created_itinerary')->default(0);
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
        Schema::dropIfExists('enquiry');
    }
}
