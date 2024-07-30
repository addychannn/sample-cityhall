<?php

namespace App\Models;

use App\Models\Address\Barangay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, HashableId, SoftDeletes;

    protected $shouldHashPersist = true;

    protected $hashColumnName = 'hash';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact_number',
        'email',
        'violator_name',
        'violator_location',
        'barangay_code',
        'violation_date',
        'landmark',
        'violation_description',
        'status',
        'admin_remarks'
    ];

    protected $hidden = [
        'id'
    ];

    // protected $appends = [
    //     'hash'
    // ];

    //allows the model to know that it has a polymorphic 
    public function gallery(){
        return $this->morphOne(Gallery::class, 'galleryable');
    }

    //relationship with barangay
    public function barangays(){
        return $this->hasOne(Barangay::class,'code','barangay_code');
    }
}
