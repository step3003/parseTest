<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parses', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->text('title');
            $table->string('location');
            $table->string('info_large');
            $table->integer('views');
            $table->string('date');
            $table->string('price');
            $table->string('phone');
            $table->text('desc');
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
        Schema::dropIfExists('parses');
    }
}
