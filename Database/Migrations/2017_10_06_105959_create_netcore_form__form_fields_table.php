<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreFormFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_form__form_fields', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->string('key');
            $table->string('name');
            $table->string('type');
            $table->text('meta')->nullable();

            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('netcore_form__forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_form__form_fields');
    }
}
