<?php

namespace Modules\Form\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Form\PassThroughs\Form\GetEntries;
use Modules\Form\Rules\FormUnique;
use Modules\Form\Translations\FormTranslation;
use Modules\Translate\Traits\SyncTranslations;

class Form extends Model
{
    use Translatable, SyncTranslations;

    /**
     * @var string
     */
    protected $table = 'netcore_form__forms';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'template',
        'has_success_view'
    ];

    /**
     * @var string
     */
    public $translationModel = FormTranslation::class;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'success_message'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function form_entries(): HasMany
    {
        return $this->hasMany(FormEntry::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entry_logs(): HasMany
    {
        return $this->hasMany(FormEntryLog::class);
    }

    /* ---------------- Other methods -------------------- */

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

    /**
     * @param $locale
     * @return array
     */
    public function formatResponse($locale)
    {
        $translation = $this->translateOrNew($locale);

        return [
            'id'              => $this->id,
            'key'             => $this->key,
            'name'            => $translation->name,
            'success_message' => $translation->success_message,
            'fields'          => $this->fields->sortBy('order')->map(function ($field) use ($locale) {
                return $field->formatResponse($locale);
            })
        ];
    }
}
