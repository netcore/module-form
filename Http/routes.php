<?php

Route::group([
    'middleware' => ['web'],
    'namespace'  => 'Modules\Form\Http\Controllers'
], function () {
    Route::put('/form/{form}', [
        'as'   => 'form::store',
        'uses' => 'FormController@store'
    ]);

    Route::get('/form/{form}/success', [
        'as'   => 'form::success',
        'uses' => 'FormController@success'
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

    Route::post('form/{form}/{field}', [
        'as'   => 'form.destroy.field',
        'uses' => 'FormController@destroyField'
    ]);

    Route::get('form/{form}/export/{type?}', [
        'as'   => 'form.export',
        'uses' => 'FormController@export'
    ]);

    Route::get('form/{form}/entries/pagination', [
        'as'   => 'form.entries.pagination',
        'uses' => 'FormEntryController@pagination'
    ]);

    Route::resource('form.entries', 'FormEntryController', [
        'only' => ['index', 'show', 'destroy'],
    ]);
});
