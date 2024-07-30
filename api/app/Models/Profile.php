<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Profile extends Model
{
    use HasFactory, HashableId;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender_id',
        'suffix',
        'nickname',
        'birthdate',
        'image_id'
    ];

    public function addresses(){
        return $this->hasMany(Address::class, 'profile_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gender() {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function image(){
        return $this->belongsTo(Images::class);
    }

    public function images(){
        return $this->belongsToMany(Images::class, 'profile_images', 'profile_id', 'image_id');
    }
}
