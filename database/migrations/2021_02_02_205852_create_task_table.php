<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('itinerary_id');
            $table->string('reference_number', 255);
            $table->string('task_name', 255);
            $table->longtext('task_des');
            $table->integer('assigned_by');
            $table->integer('assigned_to');
            $table->string('start_date', 255);
            $table->string('start_time', 255);
            $table->string('end_date', 255);
            $table->string('end_time', 255);
            $table->integer('priority');
            $table->integer('status');
            $table->softDeletes();
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
        Schema::dropIfExists('task');
    }
}
