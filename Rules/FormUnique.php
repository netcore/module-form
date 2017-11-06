<?php

namespace Modules\Form\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Form\Models\Form;

class FormUnique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $field = $this->form->fields->where('key', $attribute)->first();
        if (!$field) {
            return true;
        }

        $exists = $this->form->form_entries->where('form_field_id', $field->id)->where('value', $value)->count();
        if (!$exists) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
