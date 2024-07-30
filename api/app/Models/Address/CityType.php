<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function cities(){
        return $this->hasMany(City::class, 'type_id');
    }
}
