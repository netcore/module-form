<?php

namespace Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormEntryLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_form__form_entry_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'entry_id',
        'ip',
        'user_agent',
    ];

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
    public function entry(): BelongsTo
    {
        return $this->belongsTo(FormEntry::class, 'entry_id', 'batch');
    }
}
