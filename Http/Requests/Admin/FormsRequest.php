<?php

namespace Modules\Form\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Netcore\Translator\Helpers\TransHelper;

class FormsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'fields'       => 'required',
            'fields.*.key' => 'required'
        ];

        foreach (TransHelper::getAllLanguages() as $language) {
            $rules['translations.' . $language->iso_code . '.name'] = 'required';
            $rules['fields.*.translations.' . $language->iso_code . '.label'] = 'required';
        }

        return $rules;
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'fields.required' => 'Fields are required.'
        ];

        foreach (TransHelper::getAllLanguages() as $language) {
            $messages['translations.' . $language->iso_code . '.name.required'] = 'Name (' . strtoupper($language->iso_code) . ') is required.';
            $messages['fields.*.translations.' . $language->iso_code . '.label.required'] = 'Label (' . strtoupper($language->iso_code) . ') is required.';
        }

        return $messages;
    }
}
