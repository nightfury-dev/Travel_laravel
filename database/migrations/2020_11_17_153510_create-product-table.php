<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('product')) {
            Schema::create('product', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->integer('category');
                $table->string('country', 255)->nullable();
                $table->string('city', 255)->nullable();
                $table->string('state', 255)->nullable();
                $table->string('zip', 255)->nullable();
                $table->string('street_address', 255)->nullable();
                $table->string('position', 255)->nullable();
                $table->string('start_time');
                $table->string('end_time');
                $table->integer('supplier');
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
        Schema::dropIfExists('product');
    }
}
