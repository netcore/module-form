<?php

namespace Modules\Form\Repositories;

use Collective\Html\FormFacade;
use Modules\Form\Models\Form;

class FormsRepository
{
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
        $html = '<form method="POST" action="' . $form->getAction() .'" enctype="multipart/form-data">';
        $html .= $this->fields($form->fields);
        $html .= '</form>';

        return $html;
    }

    /**
     * @param $fields
     * @return string
     */
    private function fields($fields)
    {
        $html = '';

        foreach ($fields as $field) {
            $html .= '<div class="form-group">';
            $html .= FormFacade::label($field->key, $field->label);
            $html .= $this->{$field->type}($field);
            $html .= '</div>';
        }

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
