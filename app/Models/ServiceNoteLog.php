<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceNoteLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'service_note_id',
        'action',
        'description',
    ];

    public function serviceNote(): BelongsTo
    {
        return $this->belongsTo(ServiceNote::class);
    }
}
