<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('third_name');
            $table->date('dob');
            $table->integer('age');
            $table->string('gender');
            $table->string('pic')->nullable();
            $table->string('address')->nullable();
            $table->integer('ph_number')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('third_name');
            $table->dropColumn('dob');
            $table->dropColumn('age');
            $table->dropColumn('gender');
            $table->dropColumn('pic')->nullable();
            $table->dropColumn('address')->nullable();
            $table->dropColumn('ph_number')->unique();
        });
    }
}
