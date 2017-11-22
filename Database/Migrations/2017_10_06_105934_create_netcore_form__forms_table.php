<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreFormFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_form__forms', function (Blueprint $table) {
            $table->increments('id');

            $table->string('key')->index()->nullable();
            $table->string('template')->nullable();

            $table->timestamps();
        });

        Schema::create('netcore_form__form_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')->references('id')->on('netcore_form__forms')->onDelete('cascade');

            $table->string('locale')->index();
            $table->string('name');
            $table->string('success_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_form__form_translations');
        Schema::dropIfExists('netcore_form__forms');
    }
}
