<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'protected',
        'active',
    ];

    protected $casts = [
        'protected' => 'boolean',
        'active' => 'boolean',
    ];
}
