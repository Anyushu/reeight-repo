<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('VIEW_ID');
            $table->string('url');
            $table->string('category');
            $table->string('industry');
            $table->integer('plan')->nullable();
            $table->integer('payment_methods')->nullable();
            $table->string('site_name');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('add_sites');
    }
}
