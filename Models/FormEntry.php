<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormEntry extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_form__form_entries';

    /**
     * @var array
     */
    protected $fillable = [
        'form_id',
        'form_field_id',
        'value',
        'batch'
    ];

    /**
     * @var array
     */
    protected $with = ['form_field'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form_field()
    {
        return $this->belongsTo(FormField::class);
    }

}
