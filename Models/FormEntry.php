<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form_field(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function log(): HasOne
    {
        return $this->hasOne(FormEntryLog::class, 'batch', 'entry_id');
    }

}
