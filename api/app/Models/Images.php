<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ImageDeletingTrait;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Images extends Model
{
    use HasFactory, SoftDeletes, ImageDeletingTrait, HashableId;

    protected $fillable = [
        'name',
        'alt',
        'path',
        'mime',
        'extension',
        'status',
        'main',
        'gallery_id',
    ];

    protected $casts = [
        'main' => 'boolean'
    ];

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function gallery(){
        return $this->belongsTo(Gallery::class);
    }
}
