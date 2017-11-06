<?php

namespace Modules\Form\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Traits\SyncTranslations;
use Modules\Form\Translations\FormFieldTranslation;

class FormField extends Model
{
    use Translatable, SyncTranslations;

    /**
     * @var string
     */
    protected $table = 'netcore_form__form_fields';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'type',
        'meta',
        'order',
        'show_label'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'meta' => 'array'
    ];

    /**
     * @var string
     */
    public $translationModel = FormFieldTranslation::class;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'label',
        'placeholder'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany(FormEntry::class);
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

        if (!isset($attributes['placeholder'])) {
            $attributes['placeholder'] = $this->placeholder;
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

    /**
     * @return mixed
     */
    public function getValidationRules()
    {
        $validation = array_get($this->meta, 'validation', []);

        if (!is_array($validation)) {
            $validation = [];
        }

        return $validation;
    }

    /**
     * @return string
     */
    private function getClass()
    {
        $classes = [
            'checkbox' => '',
        ];

        return array_get($classes, $this->type, 'form-control');
    }
}
