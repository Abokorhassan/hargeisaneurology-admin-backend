<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('specialty')->nullable();
            $table->string('email')->unique();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('pic')->nullable();
            $table->string('address')->nullable();
            $table->integer('ph_number')->unique();
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
        Schema::dropIfExists('doctors');
    }
}
