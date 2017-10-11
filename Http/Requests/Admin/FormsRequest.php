<?php

namespace Modules\Form\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
        $form = $this->form;
        $uniqueRule = 'unique:netcore_form__form_fields' . ($form ? ',form_id,' . $form->id . ',id' : '');

        return [
            'name'         => 'required',
            'type'         => 'required',
            'type_value'   => 'required',

            'fields'       => 'required',
            'fields.*.key' => [
                'required',
                $uniqueRule
            ]
        ];
    }
}
