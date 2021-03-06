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
            if ($field->type == 'file') {
                $fileName = '';
                $file = $request->file($field->key);
                if ($file) {
                    $fileName = md5($form->id . $field->key . time()) . '.' . $file->getClientOriginalExtension();

                    $path = public_path($this->config['uploads_path']);
                    if (!\File::exists($path)) {
                        \File::makeDirectory($path, 0775, true);
                    }

                    $file->move($path, $fileName);
                }

                $request->{$field->key} = $fileName;
            }

            $form->form_entries()->create([
                'form_field_id' => $field->id,
                'value'         => ($field->type !== 'file') ? $request->get($field->key, '') : $fileName,
                'batch'         => $batch
            ]);
        }

        // Callback
        if (isset(self::$callables[$form->key])) {
            self::$callables[$form->key]($form->entries()->get($batch));
        }

        // Log
        $form->entry_logs()->create([
            'entry_id'   => $batch,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return true;
    }

    /**
     * @param $text
     * @return string
     */
    public function replace($text)
    {
        return preg_replace_callback('~\[form=(.*?)\]~s', function ($match) {
            $form = isset($match[1]) ? $match[1] : null;

            // $form can be string or integer. Postgresql will not allow us to search for string in an integer column (ID)
            // So, if it is string, look only in "key" column
            if (is_numeric($form)) {
                $form = Form::find($form);
            } else {
                $form = Form::where('key', $form)->first();
            }

            if (!$form) {
                return '';
            }

            return $this->form($form);
        }, $text);
    }

    /**
     * @param $form
     * @return string
     */
    public function render($form)
    {
        // $form can be string or integer. Postgresql will not allow us to search for string in an integer column (ID)
        // So, if it is string, look only in "key" column
        if (is_numeric($form)) {
            $form = Form::find($form);
        } else {
            $form = Form::where('key', $form)->first();
        }

        if (!$form) {
            return '';
        }

        return $this->form($form);
    }

    /**
     * @param Form $form
     * @return string
     */
    private function form(Form $form)
    {
        $template = $form->template ? $this->config['templates_path'] . $form->template : $this->config['templates_path'] . $form->key;

        if ($template && view()->exists($template)) {
            return view($template, [
                'form'   => $form,
                'url'    => route('form::store', $form),
                'fields' => $form->fields->sortBy('order')
            ]);
        }

        $html = view('admin::_partials._messages');
        $html .= FormFacade::open(['route' => ['form::store', $form->id], 'method' => 'PUT', 'files' => true]);
        $html .= $this->fields($form);
        $html .= FormFacade::close();

        return $html;
    }

    /**
     * @param Form $form
     * @return string
     */
    private function fields(Form $form)
    {
        $html = '';

        if ($this->config['honeypot_enabled']) {
            $html .= FormFacade::text($this->config['honeypot_field_name'], null, ['style' => 'display: none;']);
        }

        foreach ($form->fields->sortBy('order') as $field) {
            $html .= '<div class="form-group">';
            if ($field->show_label) {
                $html .= FormFacade::label($field->key, $field->label);
            }
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
