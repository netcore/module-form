<?php

namespace Modules\Form\Translations;

use Illuminate\Database\Eloquent\Model;

class FormFieldTranslation extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_form__form_field_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'label',
        'locale' // This is very important
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

}
