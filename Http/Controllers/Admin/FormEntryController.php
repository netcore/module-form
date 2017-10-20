<?php

namespace Modules\Form\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Form\Models\Form;

class FormEntryController extends Controller
{
    /**
     * @param Form $form
     * @return mixed
     */
    public function pagination(Form $form)
    {
        $entries = $form->entries()->all($limitValue = 100);

        return datatables()->of($entries)->addColumn('actions', function ($entry) use ($form) {
            return view('form::entries.tds.actions', compact('form', 'entry'))->render();
        })->rawColumns(['actions'])->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Form $form
     * @return Response
     */
    public function index(Form $form)
    {
        return view('form::entries.index', compact('form'));
    }

    /**
     * @param Form $form
     * @param      $entry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Form $form, $entry)
    {
        $entry = $form->entries()->get($entry);

        if (!$entry) {
            return redirect()->route('admin::form.entries.index', $form)->withErrors('Entry not found');
        }

        return view('form::entries.show', compact('form', 'entry'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Form $form
     * @return Response
     */
    public function destroy(Form $form, $batch)
    {
        $form->form_entries()->where('batch', $batch)->delete();

        if (request()->ajax()) {
            return response()->json([
                'state' => 'success'
            ]);
        }

        return back()->withSuccess('Successfully deleted');
    }
}
