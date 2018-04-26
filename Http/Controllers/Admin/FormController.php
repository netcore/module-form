<?php

namespace Modules\Form\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Form\Http\Requests\Admin\FormsRequest;
use Modules\Form\Models\Form;
use Modules\Form\Models\FormField;
use Modules\Form\Traits\ExportTrait;
use Modules\Form\Traits\FieldTrait;
use Netcore\Translator\Helpers\TransHelper;

class FormController extends Controller
{
    use ExportTrait, FieldTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $forms = Form::all();

        return view('form::index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $languages = TransHelper::getAllLanguages();
        $translations = $languages->mapWithKeys(function ($item, $i) {
            return [
                $i => [
                    'name'            => '',
                    'success_message' => ''
                ]
            ];
        })->toArray();
        $form = collect([
            'translations' => $translations,
            'fields'       => []
        ]);

        return view('form::create', compact('languages', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FormsRequest $request
     * @return Response
     */
    public function store(FormsRequest $request)
    {
        $form = Form::create([]);
        $form->storeTranslations($request->get('translations', []));

        // Fields
        foreach ($request->get('fields', []) as $order => $formField) {
            $field = $form->fields()->create($this->field($formField, $order, 'create'));

            $field->storeTranslations($formField['translations']);
        }

        session()->flash('success', 'Form successfully created');

        return response()->json([
            'message'  => 'Form successfully created',
            'redirect' => route('admin::form.edit', $form)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Form $form
     * @return Response
     */
    public function edit(Form $form)
    {
        $languages = TransHelper::getAllLanguages();
        $form->fields = $this->getFields($form);

        return view('form::edit', compact('form', 'languages', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormsRequest $request
     * @param Form         $form
     * @return Response
     */
    public function update(FormsRequest $request, Form $form)
    {
        $form->updateTranslations($request->get('translations', []));

        $fields = $request->get('fields', []);

        // Fields
        foreach ($fields as $formField) {
            $field = $form->fields()->where('id', $formField['id'])->first();

            if (!$field) {
                $field = $form->fields()->create($this->field($formField, null, 'create'));
                $field->storeTranslations($formField['translations']);
            } else {
                $field->update($this->field($formField, null, 'update'));
                $field->updateTranslations($formField['translations']);
            }
        }

        session()->flash('success', 'Form successfully saved');

        return response()->json([
            'message'  => 'Form successfully saved',
            'redirect' => route('admin::form.edit', $form)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Form $form
     * @return Response
     * @throws \Exception
     */
    public function destroy(Form $form)
    {
        $form->delete();

        if (request()->ajax()) {
            return response()->json([
                'state' => 'success'
            ]);
        }

        return back()->withSuccess('Successfully deleted');
    }

    /**
     * @param Form      $form
     * @param FormField $field
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyField(Form $form, FormField $field)
    {
        $field->delete();

        return response()->json([
            'state' => 'success'
        ]);
    }
}
