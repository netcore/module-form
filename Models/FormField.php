<?php

namespace Modules\Form\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Translate\Traits\SyncTranslations;
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

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(FormEntry::class);
    }

    /* ---------------- Other methods -------------------- */

    /**
     * @param bool $parsed
     * @return array
     */
    public function getAttributesData($parsed = true)
    {
        $attributes = array_get($this->meta, 'attributes', []);

        if (!is_array($attributes)) {
            $attributes = [];
        }

        return $parsed ? implode(' ', array_map(function ($v, $k) {
            return sprintf('%s=%s', $k, $v);
        }, $attributes, array_keys($attributes))) : $attributes;
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
