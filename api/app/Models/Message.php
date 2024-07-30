<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, HashableId, SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'contact',
        'message',
    ];

     //all attri are hidden before sending to the client
     protected $hidden = [
        'id',
    ];

    //all attri are appended upon model mounting
    protected $appends = [
        'hash'
    ];

     //gallery eme
     public function gallery(){
        return $this->morphOne(Gallery::class, 'galleryable');
    }
}
