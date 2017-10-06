<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Form\Models\FormField;

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
        'name',
        'type',
        'type_value'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(FormField::class);
    }
}
