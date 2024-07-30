<?php

namespace App\Models;

use App\Models\Enforcer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Files extends Model
{
    use HasFactory, HashableId,SoftDeletes;

    protected $fillable = [
        'name',
        'file_name',
        'path',
        'ext',
        'mime',
        'size',
        'submitted_by',
        'hash_md5',
        'hash_sha1',
        'hash_sha256',
        'type'

    ];

    protected $hidden = [
        'path',
    ];

     //all attri are appended upon model mounting
     protected $appends = [
        'hash'
    ];

}
