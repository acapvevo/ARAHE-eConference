<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimezoneColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('admins', 'timezone')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->string('timezone')->after('remember_token')->nullable();
            });
        }

        if (!Schema::hasColumn('participants', 'timezone')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->string('timezone')->after('remember_token')->nullable();
            });
        }

        if (!Schema::hasColumn('reviewers', 'timezone')) {
            Schema::table('reviewers', function (Blueprint $table) {
                $table->string('timezone')->after('remember_token')->nullable();
            });
        }

        if (!Schema::hasColumn('bills', 'proof')) {
            Schema::table('bills', function (Blueprint $table) {
                $table->string('proof')->nullable();
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
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });

        Schema::table('reviewers', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
}
