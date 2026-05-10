<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceNote extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'Received',
        'Checking',
        'Waiting Customer Approval',
        'In Progress',
        'Waiting Parts',
        'Completed',
        'Collected',
        'Cancelled',
    ];

    protected $fillable = [
        'service_no',
        'customer_id',
        'device_id',
        'received_date',
        'reported_issue',
        'initial_diagnosis',
        'repair_action',
        'parts_replaced',
        'service_charge',
        'parts_charge',
        'total_charge',
        'warranty_duration',
        'warranty_unit',
        'warranty_note',
        'status',
        'technician_name',
        'customer_signature_path',
        'technician_signature_path',
        'pdf_original_path',
    ];

    protected function casts(): array
    {
        return [
            'received_date' => 'date',
            'service_charge' => 'decimal:2',
            'parts_charge' => 'decimal:2',
            'total_charge' => 'decimal:2',
            'warranty_duration' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ServiceNoteLog::class);
    }
}
