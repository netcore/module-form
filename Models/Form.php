<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Form\Models\FormField;
use Modules\Form\Models\FormEntry;
use Modules\Form\PassThroughs\Form\GetEntries;
use Modules\Form\Rules\FormUnique;

class Form extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_form__forms';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(FormField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function form_entries()
    {
        return $this->hasMany(FormEntry::class);
    }

    /**
     * @return GetEntries
     */
    public function entries()
    {
        return new GetEntries($this);
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $array = [];

        foreach ($this->fields as $field) {
            if ($rules = $field->getValidationRules()) {
                foreach ($rules as $rule) {
                    $array[$field->key][] = $rule == 'unique' ? new FormUnique($this) : $rule;
                }
            }
        }

        return $array;
    }
}
