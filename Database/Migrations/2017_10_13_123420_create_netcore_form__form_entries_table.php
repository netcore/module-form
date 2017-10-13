<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreFormFormEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_form__form_entries', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')->references('id')->on('netcore_form__forms')->onDelete('cascade');
            $table->unsignedInteger('form_field_id');
            $table->foreign('form_field_id')->references('id')->on('netcore_form__form_fields')->onDelete('cascade');

            $table->text('value')->nullable();
            $table->unsignedInteger('batch');

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
        Schema::dropIfExists('netcore_form__form_entries');
    }
}
