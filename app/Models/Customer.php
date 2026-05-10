<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function serviceNotes(): HasMany
    {
        return $this->hasMany(ServiceNote::class);
    }
}
