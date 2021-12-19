<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('username', 255);
                $table->string('email', 255)->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('verification_code', 255)->nullable();
                $table->string('password');
                $table->string('first_name', 255)->nullable();
                $table->string('last_name', 255)->nullable();
                $table->integer('category')->nullable();
                $table->integer('account_type');
                $table->string('avatar_path', 255)->nullable();
                $table->string('main_country', 255)->nullable();
                $table->string('main_city', 255)->nullable();
                $table->string('main_region_state', 255)->nullable();
                $table->string('main_postal_code', 255)->nullable();
                $table->string('main_street_number', 255)->nullable();
                $table->string('main_street_address', 255)->nullable();
                $table->string('main_office_phone', 255)->nullable();
                $table->string('main_email', 255)->nullable();
                $table->string('billing_country', 255)->nullable();
                $table->string('billing_city', 255)->nullable();
                $table->string('billing_region_state', 255)->nullable();
                $table->string('billing_postal_code', 255)->nullable();
                $table->string('billing_company_name', 255)->nullable();
                $table->string('billing_street_number', 255)->nullable();
                $table->string('billing_street_address', 255)->nullable();
                $table->string('billing_office_phone', 255)->nullable();
                $table->string('billing_email', 255)->nullable();
                $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
