<?php

Route::group([
    'middleware' => ['web', 'auth.admin'],
    'prefix'     => 'admin',
    'namespace'  => 'Modules\Form\Http\Controllers'
], function () {
    Route::resource('forms', 'FormController', [
        'except' => ['show'],
        'names'  => [
            'index'   => 'admin::form.index',
            'create'  => 'admin::form.create',
            'store'   => 'admin::form.store',
            'edit'    => 'admin::form.edit',
            'update'  => 'admin::form.update',
            'destroy' => 'admin::form.destroy',
        ]
    ]);
});
