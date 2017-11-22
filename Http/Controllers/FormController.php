<?php

namespace Modules\Form\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Form\Models\Form;

class FormController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Form    $form
     * @return mixed
     */
    public function store(Request $request, Form $form)
    {
        if (!app('forms')->storeEntries($request, $form)) {
            return back();
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => $form->success_message ?: 'Successfully submitted'
            ]);
        }

        return back()->withSuccess($form->success_message ?: 'Successfully submitted');
    }
}
