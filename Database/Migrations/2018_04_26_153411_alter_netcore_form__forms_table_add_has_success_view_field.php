<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNetcoreFormFormsTableAddHasSuccessViewField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('netcore_form__forms', function (Blueprint $table) {
            $table->boolean('has_success_view')->default(0)->after('template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('netcore_form__forms', function (Blueprint $table) {
            $table->dropColumn(['has_success_view']);
        });
    }
}
