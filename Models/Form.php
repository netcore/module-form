<?php

namespace Modules\Form\Models;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Modules\Form\Models\FormField;
use Modules\Form\Models\FormEntry;

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
    public function entries()
    {
        return $this->hasMany(FormEntry::class);
    }

    /**
     * @param $request
     */
    public function storeEntries($request)
    {
        $lastEntry = $this->entries()->latest()->first();
        $lastBatch = $lastEntry ? $lastEntry->batch + 1 : 1;

        foreach ($this->fields as $field) {
            $this->entries()->create([
                'form_field_id' => $field->id,
                'value'         => $request->get($field->key, ''),
                'batch'         => $lastBatch
            ]);
        }
    }

    /**
     * @param bool $limitValue
     * @return array
     */
    public function getEntries($limitValue = false)
    {
        $entries = $this->entries->groupBy('batch')->map(function ($entry) {
            return $entry->keyBy('form_field_id');
        })->toArray();
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
}
