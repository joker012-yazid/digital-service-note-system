<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'device_type',
        'device_type_other',
        'brand_model',
        'serial_number',
        'specifications',
        'device_password',
    ];

    protected $hidden = [
        'device_password',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceNotes(): HasMany
    {
        return $this->hasMany(ServiceNote::class);
    }
}
