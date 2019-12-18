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
            $table->string('third_name')->nullable();;
            $table->date('dob')->nullable();;
            $table->integer('age')->nullable();;
            $table->string('gender')->nullable();;
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
            $table->dropColumn('pic');
            $table->dropColumn('address');
            $table->dropColumn('ph_number');
        });
    }
}
