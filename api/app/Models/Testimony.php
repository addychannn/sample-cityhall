<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimony extends Model
{
    use HasFactory, HashableId, SoftDeletes;

    protected $fillable = [
        'title',
        'body'
    ];

    protected $hidden = [
        'id'
    ];

    protected $appends = [
        'hash'
    ];

    //allows the model to know that it has a polymorphic
    public function gallery(){
        return $this->morphOne(Gallery::class, 'galleryable');
    }
}
