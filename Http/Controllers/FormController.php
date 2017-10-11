<?php

namespace Modules\Form\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Form\Http\Requests\Admin\FormsRequest;
use Modules\Form\Models\Form;
use Netcore\Translator\Helpers\TransHelper;

class FormController extends Controller
{
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
        $fields = $this->getFields();
        $routes = $this->getRoutes();

        return view('form::create', compact('languages', 'fields', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FormsRequest $request
     * @return Response
     */
    public function store(FormsRequest $request)
    {
        $form = Form::create($request->only(['name', 'type', 'type_value']));

        // Fields
        foreach ($request->get('fields', []) as $order => $formField) {
            $field = $form->fields()->create([
                'key'   => $formField['key'],
                'type'  => $formField['type'],
                'meta'  => [
                    'attributes' => isset($formField['validation']) ? $formField['validation'] : []
                ],
                'order' => $order + 1
            ]);

            $field->storeTranslations($formField['translations']);
        }

        return back()->withSuccess('Successfully created');
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
        $fields = $this->getFields($form);
        $routes = $this->getRoutes();

        return view('form::edit', compact('form', 'languages', 'fields', 'routes'));
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
        $form->update($request->only(['name', 'type', 'type_value']));

        $formFields = $form->fields->toArray();
        $newFields = $request->get('fields', []);

        // Add fields
        $fieldsToAdd = array_udiff($newFields, $formFields, function ($a, $b) {
            return strcmp($a['key'], $b['key']);
        });

        foreach ($fieldsToAdd as $formField) {
            $field = $form->fields()->where('key', $formField['key'])->first();

            if (!$field) {
                $field = $form->fields()->create([
                    'key'   => $formField['key'],
                    'type'  => $formField['type'],
                    'meta'  => [
                        'attributes' => isset($formField['validation']) ? $formField['validation'] : []
                    ],
                    'order' => $formField['order']
                ]);

                $field->storeTranslations($formField['translations']);
            } else {
                $field->update([
                    'meta'  => [
                        'attributes' => isset($formField['validation']) ? $formField['validation'] : []
                    ],
                    'order' => $formField['order']
                ]);

                $field->updateTranslations($formField['translations']);
            }
        }

        // Remove fields
        $fieldsToRemove = array_udiff($formFields, $newFields, function ($a, $b) {
            return strcmp($a['key'], $b['key']);
        });
        
        foreach ($fieldsToRemove as $formField) {
            $field = $form->fields()->where('key', $formField['key'])->first();

            if (!$field) {
                continue;
            }

            $field->delete();
        }

        return back()->withSuccess('Successfully saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Form $form
     * @return Response
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
     * @return array
     */
    private function getFields($data = null)
    {
        $currentFields = [];
        $oldFields = old('fields', []);

        if ($data) {
            $currentFields = $data->fields()->with('translations')->orderBy('order', 'ASC')->get()->toArray();
        }

        $fields = collect(array_merge($oldFields, $currentFields))->map(function ($field, $id) {
            return $this->mapField($id, $field);
        })->all();

        // Unique
        $fields = array_filter($fields, function ($value, $key) use ($fields) {
            return $key === array_search($value['key'], array_column($fields, 'key'));
        }, ARRAY_FILTER_USE_BOTH);

        return $fields;
    }

    /**
     * @param $field
     * @return array
     */
    private function mapField($id, $field)
    {
        $translations = [];
        $languages = TransHelper::getAllLanguages();

        foreach ($languages as $key => $language) {
            $translations[$language->iso_code] = [
                'label' => isset($field['translations'][$language->iso_code]) ? $field['translations'][$language->iso_code]['label'] : (isset($field['translations'][$key]['label']) ? $field['translations'][$key]['label'] : '(Not specified)')
            ];
        }

        return [
            'id'           => $id,
            'key'          => $field['key'],
            'type'         => $field['type'],
            'type_name'    => ucfirst($field['type']),
            'translations' => $translations,
            'order'        => $id + 1
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getRoutes()
    {
        $routes = [];

        foreach (\Route::getRoutes() as $route) {
            if (in_array('POST', $route->methods()) || in_array('PUT', $route->methods()) || in_array('PATCH',
                    $route->methods())) {
                if ($route->getName()) {
                    $exploded = explode('.', $route->getName());
                    if (isset($exploded[0]) && $exploded[0] == 'debugbar' || strpos($exploded[0], '::') !== false) {
                        continue;
                    }

                    $routes[] = $route->getName();
                }
            }
        }

        return collect($routes);
    }
}
