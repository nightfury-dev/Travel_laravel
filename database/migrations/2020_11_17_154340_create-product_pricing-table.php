<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('product_pricing')) {
            Schema::create('product_pricing', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('product_id');
                $table->integer('tag');
                $table->string('description', 255);
                $table->float('price');
                $table->integer('currency');
                $table->string('duration', 255);
                $table->string('blackout', 255);
                $table->string('blackout_msg', 255);
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
        Schema::dropIfExists('product_pricing');
    }
}
