<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\AddressModelTrait;

class IslandGroup extends Model
{
    use HasFactory, AddressModelTrait;
    
    protected static $sync_urls = ['https://psgc.gitlab.io/api/island-groups.json'];
    protected static $sync_uniques = ['code'];
    protected static $sync_fields = ['name'];

    protected $fillable = [
        'code',
        'name',
    ];

    public function barangays(){
        return $this->hasMany(Barangay::class, 'islandGroupCode', 'code');
    }

    public function cities(){
        return $this->hasMany(City::class, 'islandGroupCode', 'code');
    }

    public function regions(){
        return $this->hasMany(Region::class, 'islandGroupCode', 'code');
    }

    public function provinces(){
        return $this->hasMany(Province::class, 'islandGroupCode', 'code');
    }
}