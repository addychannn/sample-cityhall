<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;

use App\Traits\Helpers;

class Gallery extends Model
{
    use HasFactory, HashableId, Helpers;

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    protected static function boot(){
        parent::boot();
        $self = new self;

        self::creating(function ($model) use ($self){
            $model->slug = $self->createSlug($model->name, 'Gallery');
        });
    }
    
    public function images(){
        return $this->hasMany(Images::class, 'gallery_id');
    }
}
