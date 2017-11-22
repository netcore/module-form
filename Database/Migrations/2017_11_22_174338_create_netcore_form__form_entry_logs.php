<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetcoreFormFormEntryLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_form__form_entry_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('form_id');
            $table->foreign('form_id')->references('id')->on('netcore_form__forms')->onDelete('cascade');
            $table->unsignedInteger('entry_id');

            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
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
        Schema::dropIfExists('netcore_form__form_entry_logs');
    }
}
