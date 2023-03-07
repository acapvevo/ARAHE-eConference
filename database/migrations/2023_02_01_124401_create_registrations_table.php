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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            $table->integer('form_id');
            $table->integer('participant_id');
            $table->integer('category_id');

            $table->string('code')->unique();
            $table->string('type');
            $table->string('proof')->nullable();
            $table->integer('link')->nullable();
            $table->string('dietary');

            $table->string('status_code');

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
        Schema::dropIfExists('registrations');
    }
};
