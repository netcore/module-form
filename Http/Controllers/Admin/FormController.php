<?php

namespace Modules\Form\Http\Controllers\Admin;

use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Writers\CellWriter;
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

        return view('form::create', compact('languages', 'fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FormsRequest $request
     * @return Response
     */
    public function store(FormsRequest $request)
    {
        $form = Form::create($request->only(['name']));

        // Fields
        foreach ($request->get('fields', []) as $order => $formField) {
            $field = $form->fields()->create($this->field($formField, $order, 'create'));

            $field->storeTranslations($formField['translations']);
        }

        return redirect()->route('admin::form.index')->withSuccess('Successfully created');
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
        $form->update($request->only(['name']));

        $formFields = $form->fields->toArray();
        $newFields = $request->get('fields', []);

        // Fields
        foreach ($newFields as $formField) {
            $field = $form->fields()->where('key', $formField['key'])->first();

            if (!$field) {
                $field = $form->fields()->create($this->field($formField, null, 'create'));
                $field->storeTranslations($formField['translations']);
            } else {
                $field->update($this->field($formField, null, 'update'));
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
     * @param string $form
     * @return mixed
     */
    public function export($form = 'all', $type = 'xlsx')
    {
        if ($form == 'all') {
            $forms = Form::all();
        } else {
            $forms = Form::find($form);
        }

        if (!$forms) {
            return back()->withErrors('Form not found!');
        }

        if (!in_array($type, ['xlsx', 'csv'])) {
            $type = 'xlsx';
        }

        $name = $form == 'all' ? 'All forms' : 'Form - ' . $forms->name;
        $name .= '-' . Carbon::now()->format('d.m.Y-H:i');
        Excel::create($name, function ($excel) use ($form, $forms) {
            $excel->sheet('Forms', function ($sheet) use ($form, $forms) {

                if ($form == 'all') {
                    foreach ($forms as $key => $form) {
                        $entries = $form->entries()->count();
                        $row = $key == 0 ? 1 : $row + $entries;

                        if ($key == 0 || $row == ($row + $entries) - 1) {
                            $sheet->row($row, $form->entries()->getColumns());
                            $sheet->row($row, function (CellWriter $row) {
                                $row->setFontWeight(true);
                                $row->setBackground('#cccccc');
                            });
                        }

                        foreach ($form->entries()->all() as $key => $entry) {
                            $row++;
                            $sheet->row($row, $entry);
                        }
                    }
                } else {
                    $row = 2;

                    $sheet->row(1, $forms->entries()->getColumns());
                    $sheet->row(1, function (CellWriter $row) {
                        $row->setFontWeight(true);
                        $row->setBackground('#cccccc');
                    });

                    foreach ($forms->entries()->all() as $key => $entry) {
                        $sheet->row($row, $entry);
                        $row++;
                    }
                }
            });

        })->download($type);
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

        $fields = array_merge($oldFields, $currentFields);

        // Unique
        $fields = array_filter($fields, function ($value, $key) use ($fields) {
            return $key === array_search($value['key'], array_column($fields, 'key'));
        }, ARRAY_FILTER_USE_BOTH);

        return collect($fields)->map(function ($field, $id) {
            return $this->mapField($id, $field);
        });
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
                'name'        => isset($field['translations'][$language->iso_code]) ? $field['translations'][$language->iso_code]['label'] : (isset($field['translations'][$key]['label']) ? $field['translations'][$key]['label'] : '(Not specified)'),
                'label'       => isset($field['translations'][$language->iso_code]) ? $field['translations'][$language->iso_code]['label'] : (isset($field['translations'][$key]['label']) ? $field['translations'][$key]['label'] : ''),
                'placeholder' => isset($field['translations'][$language->iso_code]) ? $field['translations'][$language->iso_code]['placeholder'] : (isset($field['translations'][$key]['placeholder']) ? $field['translations'][$key]['placeholder'] : '')
            ];
        }

        return [
            'id'           => $id,
            'key'          => $field['key'],
            'type'         => $field['type'],
            'type_name'    => ucfirst($field['type']),
            'translations' => $translations,
            'order'        => $id + 1,
            'meta'         => $this->parseData($field, 'meta')
        ];
    }

    /**
     * @param $formField
     * @param $type
     * @return array
     */
    private function parseData($formField, $type)
    {
        $data = isset($formField[$type]) ? $formField[$type] : [];

        if ($type != 'options') {
            return $data;
        }

        if (!$data) {
            return [];
        }

        return array_combine($data['value'], $data['text']);
    }

    /**
     * @param $formField
     * @return array
     */
    private function field($formField, $order = null, $action = 'update'): array
    {
        $data = [
            'meta'       => [
                'attributes' => $this->parseData($formField, 'attributes'),
                'options'    => $this->parseData($formField, 'options'),
                'validation' => $this->parseData($formField, 'validation')
            ],
            'order'      => $order ?: $formField['order'],
            'show_label' => isset($formField['show_label']) ? 1 : 0
        ];

        if ($action == 'create') {
            $data['key'] = $formField['key'];
            $data['type'] = $formField['type'];
        }

        return $data;
    }
}
