<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\AddressModelTrait;

class Region extends Model
{
    use HasFactory, AddressModelTrait;

    protected static $sync_urls = ['https://psgc.gitlab.io/api/regions.json'];
    protected static $sync_uniques = ['code'];
    protected static $sync_fields = ['name', 'regionName', 'islandGroupCode', 'psgc10DigitCode'];

    protected $fillable = [
        'code',
        'name',
        'regionName',
        'islandGroupCode',
        'psgc10DigitCode',
    ];

    public function barangays(){
        return $this->hasMany(Barangay::class, 'regionCode', 'code');
    }

    public function cities(){
        return $this->hasMany(City::class, 'regionCode', 'code');
    }

    public function provinces(){
        return $this->hasMany(Province::class, 'regionCode', 'code');
    }

    public function islandGroup(){
        return $this->belongsTo(IslandGroup::class, 'islandGroupCode', 'code');
    }
}
