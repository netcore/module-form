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
     * @return array
     */
    public function getEntries()
    {
        $array = [];
        foreach ($this->entries()->with('form_field')->get()->groupBy('batch') as $i => $entries) {
            foreach ($entries as $entry) {
                $array[$i]['id'] = $entry->batch;
                $array[$i][$entry->form_field->key] = str_limit($entry->value, 50);
                $array[$i]['submitted_at'] = $entry->created_at->format('d.m.Y H:i');
            }
        }

        return $array;
    }
}
