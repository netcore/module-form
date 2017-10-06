<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_form__form_fields';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'type',
        'meta'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'meta' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return array
     */
    public function getAttributesData()
    {
        $attributes = array_get($this->meta, 'attributes', []);

        if (!is_array($attributes)) {
            $attributes = [];
        }

        if (!isset($attributes['class'])) {
            $attributes['class'] = $this->getClass();
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function getOptionsData()
    {
        $options = array_get($this->meta, 'options', []);
        if (is_array($options)) {
            return $options;
        }

        if (function_exists($options)) {
            $options = $options();
        }

        if (!is_array($options)) {
            return [];
        }

        return $options;
    }
}
