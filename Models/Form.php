<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Form\Models\FormField;
use Modules\Form\Models\FormEntry;
use Modules\Form\PassThroughs\Form\GetEntries;

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
        $rules = [];

        foreach ($this->fields as $field) {
            if ($rule = $field->getValidationRules()) {
                $rules[$field->key] = $rule;
            }
        }

        return $rules;
    }

    public function entryData()
    {
        $array = [];

        foreach ($entries as $i => $entry) {
            $array[$i]['id'] = $i;
            foreach ($this->fields as $f => $field) {
                $array[$i][$field->key] = isset($entry[$field->id]) ? ($limitValue ? str_limit($entry[$field->id]['value'], 50) : $entry[$field->id]['value']) : '';
                if (isset($entry[$field->id])) {
                    $array[$i]['submitted_at'] = $entry[$field->id]['created_at'];
                }
            }
        }

        return $array;
    }

    /**
     * @param $entries
     * @return array
     */
    public function getEntry($entries)
    {
        if (!$entries) {
            return [];
        }

        $entry = $entries->keyBy('form_field_id')->mapWithKeys(function ($e) {
            if ($field = $e->form_field) {
                return [$field->key => $e->value];
            }
        });

        $entry->prepend($entries[0]->batch, 'id');
        $entry->put('submitted_at', $entries[0]->created_at);

        return $entry->toArray();
    }

    /**
     * @param      $key
     * @param      $batch
     * @param null $default
     * @return mixed
     */
    public function getValue($key, $batch, $default = null)
    {
        $entry = $this->entries()->where('batch', $batch)->whereHas('form_field', function ($q) use ($key) {
            $q->where('key', $key);
        })->first();

        return $entry ? $entry->value : $default;
    }
}
