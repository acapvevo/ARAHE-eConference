<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();

            $table->integer('registration_id');
            $table->integer('duration_id');
            $table->integer('package_id');
            $table->json('extras');
            $table->integer('hotel_id')->nullable();
            $table->integer('occupancy_id')->nullable();
            $table->integer('total');
            $table->string('locality');

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
        Schema::dropIfExists('summaries');
    }
};
