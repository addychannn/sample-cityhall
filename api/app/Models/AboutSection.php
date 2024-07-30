<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gallery;
use Veelasky\LaravelHashId\Eloquent\HashableId;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutSection extends Model
{
    use HasFactory,HashableId,SoftDeletes;
    protected $fillable=[
        'title',
        'content',
    ];

    protected $hidden = [
        'id'
    ];

    protected $appends = [
        'hash'
    ];

    public function gallery(){
        return $this->MorphOne(Gallery::class,'galleryable');
    }


}
