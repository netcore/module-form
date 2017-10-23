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
            $table->foreign('form_id')->references('id')->on('netcore_form__forms')->onDelete('cascade');

            $table->string('key');
            $table->string('type');
            $table->text('meta')->nullable();
            $table->boolean('show_label')->default(1);
            $table->smallInteger('order');

            $table->timestamps();
        });

        Schema::create('netcore_form__form_field_translations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('form_field_id');
            $table->foreign('form_field_id')->references('id')->on('netcore_form__form_fields')->onDelete('cascade');

            $table->string('locale')->index();
            $table->string('label');
            $table->string('placeholder')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_form__form_field_translations');
        Schema::dropIfExists('netcore_form__form_fields');
    }
}
