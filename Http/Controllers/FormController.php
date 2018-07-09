<?php

namespace Modules\Form\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Form\Models\Form;

class FormController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Form    $form
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Form $form)
    {
        if (!app('forms')->storeEntries($request, $form)) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong, please, try again!'
                ], 422);
            }

            return back()->withError('Something went wrong, please, try again!');
        }

        if ($form->has_success_view) {
            session()->flash('form-' . $form->id . '-success', 1);
        }

        if ($request->ajax() || $request->expectsJson()) {
            return $form->has_success_view ? response()->json([
                'redirect' => route('form::success', $form)
            ]) : response()->json([
                'message' => $form->success_message ?: 'Successfully submitted'
            ]);
        }

        return $form->has_success_view ? redirect()->route('form::success',
            $form) : back()->withSuccess($form->success_message ?: 'Successfully submitted');
    }

    /**
     * @param Form $form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function success(Form $form)
    {
        if (!session()->has('form-' . $form->id . '-success')) {
            return redirect()->to('/');
        }

        $path = config('netcore.module-form.templates_path');
        $view = $form->key ? $form->key . '-success' : $form->id . '-success';
        $template = $path . $view;

        if (!$template || !view()->exists($template)) {
            return redirect()->to('/');
        }

        return view($template, [
            'form' => $form
        ]);
    }
}
