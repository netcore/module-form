<?php

Route::group(['middleware' => 'web', 'prefix' => 'form', 'namespace' => 'Modules\Form\Http\Controllers'], function()
{
    Route::get('/', 'FormController@index');
});
