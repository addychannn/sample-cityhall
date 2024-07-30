<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;

use App\Models\Address\AddressType;
use App\Models\Address\Barangay;

class Address extends Model
{
    use HasFactory, HashableId;

    protected $fillable = [
        'profile_id',
        'location',// House No./Lot/Block No./Street/Alley/Etc...
        'type_id',
        'barangayCode',
        'isMain',
    ];

    protected $casts = [
        'isMain' => 'boolean'
    ];

    public function profile(){
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function type(){
        return $this->belongsTo(AddressType::class, 'type_id');
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class, 'barangayCode', 'code');
    }

    public function city(){
        return $this->barangay->city();
    }

    public function province(){
        return $this->barangay->province();
    }

    public function region(){
        return $this->barangay->region();
    }

    public function islandGroup(){
        return $this->barangay->islandGroup();
    }
}
