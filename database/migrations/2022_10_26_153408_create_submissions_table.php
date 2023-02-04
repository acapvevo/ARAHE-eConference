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

            $table->integer('registration_id');
            $table->integer('reviewer_id')->nullable();

            $table->string('title')->unique();
            $table->text('abstract');
            $table->string('keywords');
            $table->string('abstractFile');
            $table->string('paperFile');

            $table->json('authors');
            $table->json('coAuthors');
            $table->string('presenter');

            $table->date('acceptedDate')->nullable();
            $table->date('submitDate');

            $table->integer('totalMark')->default(0);
            $table->text('comment')->nullable();
            $table->string('correctionFile')->nullable();

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
        Schema::dropIfExists('submissions');
    }
};
