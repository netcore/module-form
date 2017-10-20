<?php

namespace Modules\Form\Repositories;

use Collective\Html\FormFacade;
use Modules\Form\Models\Form;

class FormsRepository
{
    /**
     * @var array
     */
    private static $callables = [];

    /**
     * FormsRepository constructor.
     */
    public function __construct()
    {
        $this->config = config('netcore.module-form');
    }

    /**
     * @param $formKey
     * @param $callable
     */
    public static function addNewEvent($formKey, $callable)
    {
        self::$callables[$formKey] = $callable;
    }

    /**
     * @param      $request
     * @param Form $form
     * @return bool
     */
    public function storeEntries($request, Form $form)
    {
        $request->validate($form->getValidationRules());

        if ($this->config['honeypot_enabled'] && !empty($request->get($this->config['honeypot_field_name']))) {
            return false;
        }

        $lastEntry = $form->form_entries()->latest()->first();
        $batch = $lastEntry ? $lastEntry->batch + 1 : 1;

        foreach ($form->fields as $field) {
            $form->form_entries()->create([
                'form_field_id' => $field->id,
                'value'         => $request->get($field->key, ''),
                'batch'         => $batch
            ]);
        }

        // Callback
        if (isset(self::$callables[$form->key])) {
            self::$callables[$form->key]($form->entries()->get($batch));
        }

        return true;
    }

    /**
     * @param $text
     * @return string
     */
    public function replace($text)
    {
        return preg_replace_callback('~\[form=(.*?)\]~s', function ($match) {
            $formId = isset($match[1]) ? $match[1] : null;

            $form = Form::find($formId);

            if (!$form) {
                return '';
            }

            return $this->form($form);
        }, $text);
    }

    /**
     * @param Form $form
     * @return string
     */
    private function form(Form $form)
    {
        $html = view('admin::_partials._messages');
        $html .= FormFacade::open(['route' => ['form::store', $form->id], 'method' => 'PUT', 'files' => true]);
        $html .= $this->fields($form->fields->sortBy('order'));
        $html .= FormFacade::close();

        return $html;
    }

    /**
     * @param $fields
     * @return string
     */
    private function fields($fields)
    {
        $html = '';

        if ($this->config['honeypot_enabled']) {
            $html .= FormFacade::text($this->config['honeypot_field_name'], null, ['style' => 'display: none;']);
        }

        foreach ($fields as $field) {
            $html .= '<div class="form-group">';
            $html .= FormFacade::label($field->key, $field->label);
            $html .= $this->{$field->type}($field);
            $html .= '</div>';
        }

        $html .= '<button type="submit" class="btn btn-success">Submit</button>';

        return $html;
    }

    /**
     * @param $field
     * @return string
     */
    private function text($field)
    {
        return FormFacade::text($field->key, null, $field->getAttributesData());
    }

    /**
     * @param $field
     * @return string
     */
    private function textarea($field)
    {
        return FormFacade::textarea($field->key, null, $field->getAttributesData());
    }

    /**
     * @param $field
     * @return string
     */
    private function select($field)
    {
        return FormFacade::select($field->key, $field->getOptionsData(), null, $field->getAttributesData());
    }

    /**
     * @param $field
     * @return string
     */
    private function file($field)
    {
        return FormFacade::file($field->key, $field->getAttributesData());
    }

    /**
     * @param $field
     * @return string
     */
    private function checkbox($field)
    {
        return '<br>' . FormFacade::checkbox($field->key, null, null, $field->getAttributesData());
    }
}
