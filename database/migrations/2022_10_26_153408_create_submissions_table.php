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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->integer('form_id');
            $table->integer('participant_id');
            $table->text('abstract')->nullable();
            $table->string('title')->nullable()->unique();
            $table->string('paper')->nullable();
            $table->string('status_code');
            $table->integer('reviewer_id')->nullable();
            $table->integer('totalMark')->default(0);
            $table->text('comment')->nullable();
            $table->string('correction')->nullable();

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
        Schema::dropIfExists('submissions');
    }
};
