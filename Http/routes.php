<?php

Route::group([
    'middleware' => ['web'],
    'namespace'  => 'Modules\Form\Http\Controllers'
], function () {
    Route::put('/form/{form}', [
        'as'   => 'form::store',
        'uses' => 'FormController@store'
    ]);
});

Route::group([
    'middleware' => ['web', 'auth.admin'],
    'prefix'     => 'admin',
    'as'         => 'admin::',
    'namespace'  => 'Modules\Form\Http\Controllers\Admin'
], function () {
    Route::resource('form', 'FormController', [
        'except' => ['show'],
    ]);
    Route::resource('form.entries', 'FormEntryController', [
        'only' => ['index', 'destroy'],
    ]);

    Route::get('form/{form}/entries/pagination', [
        'as' => 'form.entries.pagination',
        'uses' => 'FormEntryController@pagination'
    ]);
});
