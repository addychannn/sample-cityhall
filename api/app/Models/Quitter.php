<?php

namespace App\Models;

use App\Models\Address\Barangay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quitter extends Model
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
        'preferred_date',
        'location',
        'barangay_code',
        'additional_notes',
        'status',
        'admin_remarks'
        
    ];

    //all attri are hidden before sending to the client
    protected $hidden = [
        'id',
    ];

    // //all attri are appended upon model mounting
    // protected $appends = [
    //     'hash'
    // ];

    //relationship with barangay
    public function barangays(){
        return $this->hasOne(Barangay::class, 'code','barangay_code');
    }
}
