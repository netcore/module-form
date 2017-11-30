<?php

namespace Modules\Form\Translations;

use Illuminate\Database\Eloquent\Model;

class FormTranslation extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_form__form_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'success_message',
        'locale' // This is very important
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

}
